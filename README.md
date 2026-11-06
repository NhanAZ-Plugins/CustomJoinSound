# CustomJoinSound

Play a welcome sound when players join an Axolotl-PM server

[![CI](https://github.com/NhanAZ-Plugins/CustomJoinSound/actions/workflows/build.yml/badge.svg)](https://github.com/NhanAZ-Plugins/CustomJoinSound/actions/workflows/build.yml)
[![Latest release](https://img.shields.io/github/v/release/NhanAZ-Plugins/CustomJoinSound)](https://github.com/NhanAZ-Plugins/CustomJoinSound/releases/latest)
[![License](https://img.shields.io/github/license/NhanAZ-Plugins/CustomJoinSound)](https://github.com/NhanAZ-Plugins/CustomJoinSound/blob/master/LICENSE)
[![Discord](https://img.shields.io/discord/986553214889517088?label=Discord&logo=discord&logoColor=white&color=5865F2)](https://discord.gg/j2X83ujT6c)


## Requirements and compatibility

- Axolotl-PM plugin API 5.14.0 or later in the 5.x series. API 5.14.0 introduced the resource pack offer event used by this plugin. Compatibility with every later 5.x release has not been tested.
- PHP 8.1 or later with the extensions supplied by Axolotl-PM, including ZIP support.

The [build workflow](.github/workflows/build.yml) identifies the exact Axolotl-PM source used for CI. The plugin has no external runtime library dependency.

## Installation

Download the PHAR from [GitHub Releases](https://github.com/NhanAZ-Plugins/CustomJoinSound/releases), place it in the server's `plugins` directory, and restart the server. The plugin creates a resource pack from the files bundled in the PHAR. Players must accept the pack to hear the welcome sound.

## Configuration and behavior

Set `onlyFirstTime: true` in `plugins/CustomJoinSound/config.yml` to play the sound only for players who have not joined before. The default is `false`, which plays it on every join. The resource pack is offered on every login regardless of this setting so it remains available when the sound is played.

The generated `.mcpack` in the plugin data directory is a cache derived from the bundled files. Its filename includes a content hash; it can be recreated if removed while the server is stopped. Existing player data and server resource packs are not modified.

## Build and development

The [build workflow](.github/workflows/build.yml) runs PHPStan at maximum level and uses [DevTools](https://github.com/AxolotlBucketLabs/DevTools) to build and inspect the plugin PHAR. CI pins the tool and server revisions. GitHub's default run title keeps push runs identifiable by commit message.

## License and support

The source is distributed under [GPL-3.0](LICENSE). The included sound and icon remain the existing project assets; their original source and separate reuse rights have not been independently established. Report bugs through [GitHub issues](https://github.com/NhanAZ-Plugins/CustomJoinSound/issues) or join [NhanAZ Discord](https://discord.gg/j2X83ujT6c).
