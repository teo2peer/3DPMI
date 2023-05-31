# 3DPMI (3D Printing Manager & Inventory)

![3DPMI Logo](https://teodin.com/assets/content/3DPMI/logo/svg/logo-color.svg)

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

3DPMI is a comprehensive solution designed to streamline and optimize the management of 3D printers, gcodes, and inventory in a seamless manner. It provides a user-friendly interface that empowers users to efficiently handle various aspects of 3D printing, including file management, printer control, and filament tracking and inventory management.

## Key Features

- **File Management**: Easily upload and manage 3D print files with easy Gcode upload from popular slicing software like Cura. The system automatically detects important print details such as estimated print time, filament weight used, and meters of filament required.

- **User Login System**: Secure user login via Google Account, which allows each user to have their own dedicated space for managing prints and uploads. Control over who submits a print job and who uploads files ensures accountability and access control within the system.

- **Filament Management**: Advanced filament management capabilities enable users to monitor and track the filament used for each print job. The comprehensive filament inventory system allows users to keep track of filament stock levels, material types, and easily reorder when necessary.

- **Inventory Control**: Beyond 3D printing, 3DPMI offers a flexible inventory control system. Define zones and sections to facilitate efficient organization of items. Integrated barcode scanning functionality ensures accurate and convenient item tracking and retrieval within the inventory.

## To-Do

- [ ] Integration with OctoPrint API

## Installation

1. Clone this repository.
2. Fill in the database information and `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT` in the `.env` file.
3. Migrate the database.
4. [Additional installation steps, if any.]

## License


Created by [Teo2Peer](https://www.linkedin.com/in/teodin), 3DPMI streamlines your 3D printing processes and offers powerful inventory management capabilities, making it an essential tool for optimizing your 3D printing and inventory workflows.

Visit [teodin.com](https://teodin.com) to learn more about Teo2Peer's projects and services.
