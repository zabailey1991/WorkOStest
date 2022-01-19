# frozen_string_literal: true

require 'dotenv/load'
require 'sinatra'
require 'workos'
require 'json'

# Pull API key and Client ID from ENV variables
WorkOS.key = ENV['WORKOS_API_KEY']
CLIENT_ID = ENV['WORKOS_CLIENT_ID']

# Configure your domain and redirect_uris at
# https://dashboard.workos.com/configuration
DOMAIN = 'foo-corp.com'
REDIRECT_URI = 'http://localhost:4567/callback'

use(
  Rack::Session::Cookie,
  key: 'rack.session',
  domain: 'localhost',
  path: '/',
  expire_after: 2_592_000,
  secret: SecureRandom.hex(16)
)

get '/' do
  @current_user = session[:user] && JSON.pretty_generate(session[:user])

  erb :index, :layout => :layout
end

# Authenticate a user by sending them to the WorkOS API
# You can also use connection or provider parameters
# in place of the domain parameter
# https://workos.com/docs/reference/sso/authorize/get
get '/auth' do
  authorization_url = WorkOS::SSO.authorization_url(
    domain: DOMAIN,
    client_id: CLIENT_ID,
    redirect_uri: REDIRECT_URI,
  )

  redirect authorization_url
end

# Exchange a code for a user profile at the callback route
get '/callback' do
  profile_and_token = WorkOS::SSO.profile_and_token(
    code: params['code'],
    client_id: CLIENT_ID,
  )

  profile = profile_and_token.profile

  session[:user] = profile.to_json

  redirect '/'
end

# Logout a user
get '/logout' do
  session[:user] = nil

  redirect '/'
end
