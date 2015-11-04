## Jackpot Bowling 

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/downloads.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

As a part of our interview process, I have build a webapp that integrates with the RESTful JSON API.

The app will be a simple way for a bowling league to track a progressive jackpot. Bowlers in the league can buy tickets to enter the drawing. All tickets cost $10 dollars, and the proceeds go into the jackpot. After some tickets are bought, one ticket is drawn at random. The owner of that ticket gets to roll once. If they get a strike, they win the entire pot. Otherwise, they win a fraction of the pot, and the remaining pot rolls over into the next drawing.

## Solution Requirements
<ul>
<li>Sign up</li>
<li>Log in</li>
<li>View a league's current jackpot</li>
<li>Let a league's bowlers buy tickets for the current jackpot</li>
<li>Draw a winning ticket for a jackpot</li>
<li>Record the result of the jackpot roll and then see the next jackpot</li>
<li>View the history of a league's jackpots</li>
</ul>

## Technology and Tools

For this application, a PHP framework : Laravel is used.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
