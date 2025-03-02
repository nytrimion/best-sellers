## About Best Sellers API

This Laravel application wraps the [New York Times Books API](https://developer.nytimes.com/docs/books-product/1/overview).
This API supports 2 versions:

- version 1: each request calls the New York Times API
- version 2: performance boost through caching New York Times API calls

## Installation

Update `.env` configuration file according to `.env.example`:

- `NYT_API_URL` New York Times API URL
- `NYT_API_KEY` New York Times API Key
- `NYT_API_RETRIES` New York Times API max request attempts
- `NYT_API_TIMEOUT` New York Times API request timeout
- `BOOK_CACHE_LIFETIME` Book repository cache lifetime 

## Usage

### Version 1

`/api/v1/best-sellers?author=John&title=Whatever&isbn[]=0553293389&isbn[]=9780553293388&offset=20`

### Version 2

`/api/v2/best-sellers?author=John&title=Whatever&isbn[]=0553293389&isbn[]=9780553293388&offset=20`
