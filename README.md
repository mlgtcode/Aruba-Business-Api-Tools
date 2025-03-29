---
# NEW VERSION https://github.com/mlgtcode/Aruba-Business-MyDashboard
---
# Price List Page

The **Price List Page** is a dedicated web interface designed to display service pricing details retrieved from the `/api/pricelist` endpoint of the Aruba Business API. Its main features include:

- **Data Retrieval:**  
  The page makes a GET request to the `/api/pricelist` endpoint to fetch a comprehensive list of pricing details for products and services.

- **Price Adjustments:**  
  Additionalfunctionality can be used to adjust the displayed prices, such as applying a percentage increase, based on dynamic computations or business rules.
  
- **CSV Export:**  
  Eyport the table as .csv

# Client List Page

The **Client List Page** is intended for managing and reviewing the details of endusers (clients) associated with Aruba business services. This page performs the following functions:

- **Client Data Fetching:**  
  It retrieves the list of clients from the `/api/endusers` endpoint, which returns an array of client information such as usernames, names, email addresses, contact details, and more.

- **User-Friendly Display:**  
  The page renders the client information in an interactive list (using Bootstrap's list group), making it easy for administrators to browse through and manage client records.

- **Administrative Management:**  
  This page serves as a central dashboard where administrators can oversee client data.
  
# Important Note

**Security & Environment Requirements:**  
- **Protected Environment:**  
  All scripts that interact with the Aruba Business API must be run in a secure, protected environment. This is to ensure sensitive credentials and API tokens are safeguarded from unauthorized access.

- **Dedicated Account and OTP:**  
  For these scripts to work reliably, a secondary account with One-Time Password (OTP) disabled is required. If OTP is enabled, the OTP has to be passed to the API request as well.

*Always follow best security practices when deploying and running these scripts, especially in a production environment.*

# Aruba Business API Overview

The **Aruba Business API** is a RESTful web service that provides programmatic access to business services offered by Aruba. It allows developers to interact with various aspects of Aruba's business platform, such as authentication, pricing management, client management, and more. The API works as follows:

- **Authentication & Token Acquisition:**  
  Users authenticate using an API key, username, and password to obtain an access token. This token is then used to authorize all subsequent API requests.

- **RESTful Endpoints:**  
  The API exposes endpoints (e.g., `/api/pricelist`, `/api/endusers`) that allow clients to perform standard HTTP operations (GET, POST, PUT, etc.) to create, read, update, or delete resources.
    See also https://api.arubabusiness.it/docs/
