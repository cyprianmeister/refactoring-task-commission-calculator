#!/bin/bash

build() {
    docker build . -t commission-calculator-image
}

install() {
    docker run -it -v ./:/var/app commission-calculator-image composer install
}

qa() {
    docker run -it -v ./:/var/app commission-calculator-image composer test
}

run() {
    docker run -it -v ./:/var/app commission-calculator-image bin/console calculate input.txt
}

run-all() {
    build
    install
    qa
    run
}

"$@"
