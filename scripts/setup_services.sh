#!/bin/bash

cd ..
cd VMs

cd database
cd services
cd authentication-receiver
./service_setup.sh
cd ..
cd search-db-receiver
./service_setup.sh
cd ..
cd ..
cd ..

cd dmz
cd services
cd search-dmz-receiver
./service_setup.sh
cd ..
cd ..
cd ..
