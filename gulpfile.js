var gulp = require('gulp');
var gutil = require('gulp-util');
var elixir = require('laravel-elixir');


elixir(function(mix) {
  mix.sass('app.sass');
})
