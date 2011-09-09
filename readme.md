# ls-module-press
Provides press releases for your store.

## Installation
1. Download [Press](https://github.com/limewheel/ls-module-press/zipball/master)
1. Create a folder named `press` in the `modules` directory.
1. Extract all files into the `modules/press` directory (`modules/press/readme.md` should exist).
1. Setup your code as described in the `Usage` section.
1. Done!

## Usage
Create a `Press Releases` page that uses the `press:articles` page action, and use this content:

```php
<? foreach($articles as $article): ?>
	<h2><?= h($article->title) ?></h2>
  <br />
  <?= h($article->content) ?>
<? endforeach ?>
```