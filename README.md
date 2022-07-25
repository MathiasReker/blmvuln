<div id="top"></div>

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<div align="center">
<h3>Fix Major Security Vulnerability on PrestaShop Websites :rocket:</h3>
  <p>
    CVE-2022-31101 detector and fixer!
  </p>
</div>

## A newly found exploit could allow remote attackers to take control of your shop

Read more the vulnerability
here: [https://build.prestashop.com/news/major-security-vulnerability-on-prestashop-websites/](https://build.prestashop.com/news/major-security-vulnerability-on-prestashop-websites/).

This module can fix the vulnerability.

The module is also designed to remove malware from infected websites! A typical attack where the attacker replaces the
checkout with a fake one. The module finds the infected files by patterns, replaces them with the original files, and
deletes malware files added by the attacker.

[![An old rock in the desert](https://user-images.githubusercontent.com/26626066/180667355-5731989b-f917-497e-b459-7488b1b8576d.png "Screenshot")]([https://www.flickr.com/photos/beaurogers/31833779864/in/photolist-Qv3rFw-34mt9F-a9Cmfy-5Ha3Zi-9msKdv-o3hgjr-hWpUte-4WMsJ1-KUQ8N-deshUb-vssBD-6CQci6-8AFCiD-zsJWT-nNfsgB-dPDwZJ-bn9JGn-5HtSXY-6CUhAL-a4UTXB-ugPum-KUPSo-fBLNm-6CUmpy-4WMsc9-8a7D3T-83KJev-6CQ2bK-nNusHJ-a78rQH-nw3NvT-7aq2qf-8wwBso-3nNceh-ugSKP-4mh4kh-bbeeqH-a7biME-q3PtTf-brFpgb-cg38zw-bXMZc-nJPELD-f58Lmo-bXMYG-bz8AAi-bxNtNT-bXMYi-bXMY6-bXMYv](https://user-images.githubusercontent.com/26626066/180667355-5731989b-f917-497e-b459-7488b1b8576d.png))

### Install the module

1. Download the latest version of the module: https://github.com/MathiasReker/blmvuln/releases/latest

2. Login into your shop's back office

3. Go to "Module Manager"

4. Click on "Upload a Module"

5. Upload and install the module

## Usage

1. Open the module and click on "Run the cleaning process".

2. After running the cleaning process, you can uninstall the module.

## Compatibility

*The module requires PrestaShop 1.7.1+ and PHP 7.1.*

## Roadmap

- [x] Scan for common patterns
- [x] Fix infected files
- [x] Compatible with PrestaShop 1.7.1
- [ ] Backward compatible with PrestaShop 1.6.1

See the [open issues](https://github.com/MathiasReker/blmvuln/issues) for a complete list of proposed features (and
known
issues).

<p align="right">(<a href="#top">back to top</a>)</p>

## Contributing

If you have a suggestion to improve this, please fork the repo and create a pull request. You can also open an issue
with the tag "enhancement". Finally, don't forget to give the project a star! Thanks again!

<p align="right">(<a href="#top">back to top</a>)</p>

## License

It is distributed under the MIT License. See `LICENSE` for more information.

<p align="right">(<a href="#top">back to top</a>)</p>

[contributors-shield]: https://img.shields.io/github/contributors/MathiasReker/blmvuln.svg

[contributors-url]: https://github.com/MathiasReker/blmvuln/graphs/contributors

[forks-shield]: https://img.shields.io/github/forks/MathiasReker/blmvuln.svg

[forks-url]: https://github.com/MathiasReker/blmvuln/network/members

[stars-shield]: https://img.shields.io/github/stars/MathiasReker/blmvuln.svg

[stars-url]: https://github.com/MathiasReker/blmvuln/stargazers

[issues-shield]: https://img.shields.io/github/issues/MathiasReker/blmvuln.svg

[issues-url]: https://github.com/MathiasReker/blmvuln/issues

[license-shield]: https://img.shields.io/github/license/MathiasReker/blmvuln.svg

[license-url]: https://github.com/MathiasReker/blmvuln/blob/develop/LICENSE.txt
