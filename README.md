# Short introduction

App parameters can be changed via .env file.  
Original input.txt file is attached in the root of the project.

Please read instructions below.

# Running the app

**To run all following commands one after another**

```bash
./make run-all
```

or, to build app image

```bash
./make build
```

to install dependencies

```bash
./make install
```

to run tests

```bash
./make qa
```

to run app

```bash
./make run
```

# Significant files

##  `.env` file

```dotenv
APP_NAME='TransactionCommissionCalculator'
APP_VERSION='1.0'
APP_ENV='prod'
APP_DEBUG='false'

TARGET_CURRENCY='EUR'
COUNTRIES_COLLECTION='["AT", "BE", "BG", "CY", "CZ", "DE", "DK", "EE", "ES", "FI", "FR", "GR", "HR", "HU", "IE", "IT", "LT", "LU", "LV", "MT","NL", "PO", "PT", "RO", "SE", "SI", "SK"]'
IN_COLLECTION_MULTIPLIER=0.01
NOT_IN_COLLECTION_MULTIPLIER=0.02

RATES_PROVIDER_API_KEY='VQpvy8vtkzWSaXpcCa8laWDUe60oRLDF'
```

##  `input.txt` file

```json lines
{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}
```

# // TODO:

1. Write more tests
2. Utilize cache for providing rates and country information 
3. Probably would refactor namespaces and folder structure
4. Add more static code analysis


