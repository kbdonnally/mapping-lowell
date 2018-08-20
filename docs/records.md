# Records

*Progress and process of Omeka-based work that doesn't show up in Git history. (Most prominently, but not exclusively, Neatline exhibits.)*

## April 1, 2018

- Neatline editor: add default zoom
	- Hard for people to get back to the overview that contains both Boston & Lowell like the one that shows on load.
	- Providing a button for that makes it easier to switch between views as a user, and eliminates stress of realizing there's no way to get back to the original view you had on load.
	- *NB: just labeling it 'Default View' for right now, but name can be changed to whatever is most appropriate.*

- Article on how to create a custom Neatline theme [here](http://neatline.org/2014/04/01/creating-themes-for-individual-neatline-exhibits/)

## April 7, 2018

- Neatline editor: add above edits to actual site, not just local Neatline copy

- From the site: 

>### Getting started: Creating the theme directory
> 
>Neatline themes are created as directories that sit inside of the Omeka theme. For any given exhibit, Neatline will look for a theme directory that has the same name as the “URL slug” of the exhibit, the unique, plain-text identifier used to form the end of the exhibit’s public-facing URL. So, imagine you’ve got an exhibit called “Test Exhibit,” with a URL slug of `test-exhibit`. To create a theme for the exhibit, create a directory called test-exhibit at this location relative to the root of your Omeka theme:
> 
>```[omeka-theme]/neatline/exhibits/themes/test-exhibit```

- **Side Note:** this (below) seems... not great. I really don't like the attitude implied here and suspect it may have something to do with why Omeka/Neatline documentation thus far has been shoddy. Maybe I'm just naive; I just feel like people should have access to the code with a disclaimer sooner rather than later. From the Neatline theme tutorial:

> I’ve held off on documenting this publicly because I wanted to be sure that the file structure and Javacsript APIs used in the themes worked well at scale – but at this point it’s all pretty battle tested, and I’m curious to see what other folks can come up with!

Especially because people are less likely to do documentation in a systematized way afterwards rather than at the time, but they could've had this whole thing written up in advance, to be sure. (Ensure it's not Jeremy who wrote this though, before you mention it to him with such a tone; also, keep in mind there might be perfectly legitimate reasons why people do this and you're just reading it in a biased way because of your prior context.) Like, the file structure is something people should be able to understand!!! Agh.

### Our GitHub:

- This will prob be useful when we're taking on the new Neatline: [our repo](https://github.com/scholarslab/neatline-theme-template) containing Neatline custom theme starter files.

## April 8, 2018

- Cool so we legit don't explain anywhere that we use Underscore.js templating for Neatline. ("We" being the default voice I'm taking with this; the SLab could've had nothing to do with that lack of documentation.)
	- The place where the templates are printed is preceded with a comment that says "Underscore templates.", and the templates are in a directory called `underscore`, but it's not at all clear *why* it's called that contextually.
	- (Like, I have a note on my hand-copy of the `exhibit.php` partial that says "what's underscore mean? is dir but name confusing". I discovered it after looking up what `rv-html` meant, although it's kind of happenstance that I put that together; I probably wouldn't have if I didn't go on the Rivets.js website.)

# Part II: August 2018 Notes

- Date: **8/15/18**

## Where things stand:

1. Tbh, trying to remember what all I had figured out...
2. Big tasks:
	- Put navbar into dynamic view file
	- Change colors in exhibit
	- Fix text layout in exhibit
	- Ideally, have each text section have a "read more..." button, since they're quite long
	- Make the name of each item come up on hover (highlighted areas on map)
	- *Possibly* format the popups on exhibit items
3. Big questions:
	- Where are the relevant CSS files, and how can I edit them?
	- Have I solved this problem before?
	- Where is the navbar stored?
	- What is this JS templating nonsense I was talking abbout? Do I need to mess with it?

### File locations

- Where to put Neatline theme customizations:

```git
> lowell/themes/maplowell/neatline/exhibits/themes/following-the-money
```

- Structure of Neatline theme:

```git
following-the-money/
	- assets/
		- css/
			- _partials/
			- style.scss
		- js/
	- style.css
	- script.js
	- show.php
	- item.php
```

- With asset preprocessing via taskrunner:

```git
following-the-money/
	- assets/
		- css/
			- _partials/
			- style.scss
		- js/
	- node_modules/
	- style.css
	- script.js
	- show.php
	- item.php
	- .babelrc
	- gulpfile.js
	- package.json
	- package-lock.json
```

### Useful functions:

1. **# Records in Exhibit:** Views.php, line 110: return number of records in exhibit

```php
/**
 * Count the records in an exhibit.
 *
 * @param NeatlineExhibit $exhibit The exhibit record.
 * @return integer The number of records.
 */

function nl_getExhibitRecordCount($exhibit=null)
{
    $exhibit = $exhibit ? $exhibit : nl_getExhibit();
    return (int) $exhibit->getNumberOfRecords();
}
```

...Right below, same file: return markup for exhibit (map stuff) & narrative (text stuff), respectively

2. **HTML for Map:**

```php
/**
 * Render and return the exhibit partial.
 *
 * @return string The exhibit markup.
 */

function nl_getExhibitMarkup()
{
    return get_view()->partial('exhibits/partials/exhibit.php');
}
```

3. **HTML for Narrative:**

```php
/**
 * Render and return the exhibit narrative partial.
 *
 * @return string The narrative markup.
 */

function nl_getNarrativeMarkup()
{
    return get_view()->partial('exhibits/partials/narrative.php');
}
```

4. **Exhibit JSON:** Neatline Globals function used in `exhibit.php` to return exhibit data:

```php
/**
 * Gather global properties exposed via the `neatline_globals` filter.
 *
 * @param NeatlineExhibit $exhibit The exhibit.
 * @return array The modified array of key => values.
 */

function nl_getGlobals($exhibit)
{
    return apply_filters('neatline_globals', array(), array(
        'exhibit' => $exhibit
    ));
}
```