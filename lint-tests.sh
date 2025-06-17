#!/bin/bash

# Run PHPStan with the custom rules for tests
./vendor/bin/phpstan analyse --configuration=phpstan-tests.neon
