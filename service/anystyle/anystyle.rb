#!/usr/bin/env ruby

require 'sinatra'
require 'anystyle/parser'
require 'json'
 
set :port => 8080
post '/parse' do
    content_type :json
    output = []
    input = params[:citation]
    input.each_with_index {  |val, index|
            obj = Anystyle.parse """
            "+val+"
            """
            output.concat([obj]) 
    }
    JSON.generate(output)
end

get '/' do
    content_type :json
    ["hi"]
end


# 404 Error!
not_found do
  status 404
  erb:error404
end
