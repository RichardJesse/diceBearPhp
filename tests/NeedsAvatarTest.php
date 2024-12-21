<?php

namespace tests;

use Faker\Factory;
use JesseRichard\DiceBearPhp\Generators\AvatarGenerators;

beforeEach( function () {
    $this->avatarGenerator = new AvatarGenerators();
    $this->faker = Factory::create();
});

it("generates an avatar URL without parameters", function () {
    expect($this->avatarGenerator->randomStyle()->url())->toMatch('/https:\/\/api\.dicebear\.com\/9\.x\/[a-zA-Z0-9\-]+\/png/');
});

it("generates an avatar URL with specified format (functional style)", function (){
    expect($this->avatarGenerator->thumbs('jpg')->url())->toBe('https://api.dicebear.com/9.x/thumbs/jpg');

});

it("generates an avatar URL with specified format (functional style) long name style", function (){
    expect($this->avatarGenerator->adventurerNeutral('jpg')->url())->toBe('https://api.dicebear.com/9.x/adventurer-neutral/jpg');

});

it("generates an avatar URL with a specified seed parameter", function (){
    $name = "jesse Richard";
    expect($this->avatarGenerator->seed($name)->randomStyle()->url())->toMatch('/https:\/\/api\.dicebear\.com\/9\.x\/[a-zA-Z0-9\-]+\/png\?seed=jesse\+Richard/');
});

it("downloads the image that is produced", function () {
    expect($this->avatarGenerator->randomStyle()->saveImage("uploads"))->toBe(true);
} );

it("gets the correct file path where the image is stored", function () {

    $this->avatarGenerator->randomStyle()->format('png'); 
    $imageSaved = $this->avatarGenerator->saveImage("uploads");
    expect($imageSaved)->toBeTrue(); 

    $savedPath = $this->avatarGenerator->savedPath(); 
    expect(file_exists($savedPath))->toBe(true); 
});

// it("generates an avatar URL with flip query string", function () {
//     expect($this->avatarGenerator->randomStyle()->flip()->url())->toMatch('/https:\/\/api\.dicebear\.com\/9\.x\/[a-zA-Z0-9\-]+\/png\?flip=true/');
// });




