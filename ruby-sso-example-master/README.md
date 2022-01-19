# ruby-sso-example

An example Sinatra application demonstrating how SSO works with WorkOS and Ruby.

## Clone and Install

Clone this repo and install dependencies:

```sh
git clone https://github.com/workos-inc/ruby-sso-example.git && cd ruby-sso-example && bundle install
```

## Configure your environment

1. Grab your [API Key](https://dashboard.workos.com/api-keys) and your [Client ID](https://dashboard.workos.com/configuration).
2. Run `cp .env.example .env` and add your API key and Client ID. The `workos` gem will read your API key from the ENV variable `WORKOS_API_KEY` and your Client ID from the ENV variable `WORKOS_CLIENT_ID`. You may also set the API key and Client ID yourself by adding `WorkOS.key = $YOUR_API_KEY` and `CLIENT_ID = $YOUR_CLIENT_ID` to `app.rb`.
3. Create an [Organization](https://dashboard.workos.com/organizations) and an SSO Connection within that organization.
4. Add a [Redirect URI](https://dashboard.workos.com/configuration) with the value `http://localhost:4567/callback`.
5. Update `app.rb`:

```ruby
DOMAIN = "$YOUR_CONNECTIONS_DOMAIN"
REDIRECT_URI = "$YOUR_REDIRECT_URI"
```

## Run the app and log in using SSO

```sh
ruby app.rb
```

Head to `http://localhost:4567` and click Sign In to authenticate!

For more information, see the [WorkOS Ruby SDK documentation](https://docs.workos.com/sdk/ruby).
