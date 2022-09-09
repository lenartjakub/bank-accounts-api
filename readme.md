# bank-account-api #

### Set up project ###

* When we have project on local machine, we can build our app command:
    ```
    make build
    ```
* Next step will be run our api:
    ```
    make up
    ```
* Before we are go to inside our app, check if you have this line in local/etc/hosts:
  ```
  127.0.0.1 localhost
  ```
* Now, we can go to inside docker container, so use command, and we will be inside app:
  ```
  make bash
  ```
* When we are inside our app, we can runn our dependencies:
  ```
  make init
  ```

### For testing the app we can use created bank account and wallet ###
  ```
  personal id number: 00000
  iban: 0002
  ```

## Endpoints ###

| Endpoint                             | Method | Data                                                                                                                               | Description                                |
|--------------------------------------|--------|------------------------------------------------------------------------------------------------------------------------------------|--------------------------------------------|
| api/wallet/withdraw                  | POST   | {<br />"<font color="#ff8585">iban</font>": "string", <br />"<font color="#ff8585">amount</font>": "float" <br /> }                | Withdraw funds from wallet                 |
| api/wallet/deposit                   | POST   | {<br />"<font color="#ff8585">iban</font>": "string", <br />"<font color="#ff8585">amount</font>": "float" <br /> }                | Deposit funds to wallet                    |
| api/wallet                           | POST   | {<br />"<font color="#ff8585">personalIdNumber</font>": "string", <br />"<font color="#ff8585">currency</font>": "string" <br /> } | Add new wallet to bank account             |
| api/wallet/{iban}                    | GET    |                                                                                                                                    | Show information about wallet and balance  |
| api/wallet/{iban}/history/{fileType} | GET    |                                                                                                                                    | generate wallet history to file (only csv) |
