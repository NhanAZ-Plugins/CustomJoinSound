<?php

declare(strict_types=1);

namespace NhanAZ\CustomJoinSound;

use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerResourcePackOfferEvent;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\resourcepacks\ZippedResourcePack;
use RuntimeException;
use ZipArchive;

class Main extends PluginBase implements Listener {
	private ZippedResourcePack $resourcePack;

	protected function onEnable(): void {
		$this->saveDefaultConfig();
		$this->resourcePack = $this->loadResourcePack();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	private function loadResourcePack(): ZippedResourcePack {
		$files = [
			"manifest.json",
			"pack_icon.png",
			"sounds/sound_definitions.json",
			"sounds/CustomJoinSound.ogg"
		];
		$contents = [];
		$hash = hash_init("sha256");
		foreach ($files as $file) {
			$stream = $this->getResource("CustomJoinSound Pack/" . $file);
			if ($stream === null) {
				throw new RuntimeException("Missing resource pack file: " . $file);
			}
			$data = stream_get_contents($stream);
			fclose($stream);
			if ($data === false) {
				throw new RuntimeException("Unable to read resource pack file: " . $file);
			}
			$contents[$file] = $data;
			hash_update($hash, $file . "\0" . $data);
		}

		$path = $this->getDataFolder() . "generated-" . hash_final($hash) . ".mcpack";
		if (!is_file($path)) {
			$zip = new ZipArchive();
			if ($zip->open($path, ZipArchive::CREATE | ZipArchive::EXCL) !== true) {
				throw new RuntimeException("Unable to create resource pack: " . $path);
			}
			foreach ($contents as $file => $data) {
				if (!$zip->addFromString($file, $data)) {
					$zip->close();
					throw new RuntimeException("Unable to add resource pack file: " . $file);
				}
			}
			if (!$zip->close()) {
				throw new RuntimeException("Unable to finish resource pack: " . $path);
			}
		}

		return new ZippedResourcePack($path);
	}

	public function onResourcePackOffer(PlayerResourcePackOfferEvent $event): void {
		$event->addResourcePack($this->resourcePack);
		$event->setMustAccept(true);
	}

	private function sendWelcomeSound(Player $player): void {
		$position = $player->getPosition();
		$packet = new PlaySoundPacket();
		$packet->soundName = "CustomJoinSound";
		$packet->x = $position->getX();
		$packet->y = $position->getY();
		$packet->z = $position->getZ();
		$packet->volume = 1.0;
		$packet->pitch = 1.0;
		$player->getNetworkSession()->sendDataPacket($packet);
	}

	public function onJoin(PlayerJoinEvent $event): void {
		$player = $event->getPlayer();
		if ($this->getConfig()->get("onlyFirstTime", false)) {
			if (!$event->getPlayer()->hasPlayedBefore()) {
				$this->sendWelcomeSound($player);
			}
		} else {
			$this->sendWelcomeSound($player);
		}
	}
}
