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

>Neatline themes are created as directories that sit inside of the Omeka theme. For any given exhibit, Neatline will look for a theme directory that has the same name as the “URL slug” of the exhibit, the unique, plain-text identifier used to form the end of the exhibit’s public-facing URL. So, imagine you’ve got an exhibit called “Test Exhibit,” with a URL slug of `test-exhibit`. To create a theme for the exhibit, create a directory called test-exhibit at this location relative to the root of your Omeka theme:

>```[omeka-theme]/neatline/exhibits/themes/test-exhibit```

- ** Side Note:** this (below) seems... not great. I really don't like the attitude implied here and suspect it may have something to do with why Omeka/Neatline documentation thus far has been shoddy. Maybe I'm just naive; I just feel like people should have access to the code with a disclaimer sooner rather than later. From the Neatline theme tutorial:

> I’ve held off on documenting this publicly because I wanted to be sure that the file structure and Javacsript APIs used in the themes worked well at scale – but at this point it’s all pretty battle tested, and I’m curious to see what other folks can come up with!

Especially because people are less likely to do documentation in a systematized way afterwards rather than at the time, but they could've had this whole thing written up in advance, to be sure. (Ensure it's not Jeremy who wrote this though, before you mention it to him with such a tone; also, keep in mind there might be perfectly legitimate reasons why people do this and you're just reading it in a biased way because of your prior context.) Like, the file structure is something people should be able to understand!!! Agh.

### Our GitHub:

- This will prob be useful when we're taking on the new Neatline: [our repo](https://github.com/scholarslab/neatline-theme-template) containing Neatline custom theme starter files.

## April 8, 2018

- Cool so we legit don't explain anywhere that we use Underscore.js templating for Neatline. ("We" being the default voice I'm taking with this; the SLab could've had nothing to do with that lack of documentation.)
	- The place where the templates are printed is preceded with a comment that says "Underscore templates.", and the templates are in a directory called `underscore`, but it's not at all clear *why* it's called that contextually.
	- (Like, I have a note on my hand-copy of the `exhibit.php` partial that says "what's underscore mean? is dir but name confusing". I discovered it after looking up what `rv-html` meant, although it's kind of happenstance that I put that together; I probably wouldn't have if I didn't go on the Rivets.js website.)

## April 10, 2018

*Check-in day!*

### Questions/Notes for Mike:

1. Spent the weekend looking through Neatline source files so I knew how to edit things behind the scenes without it affecting your and other contributors' ability to edit the actual content of the site.
	- Basically, have figured out how to style things without having to manually go in and change entries etc. after they've been created. Otherwise, the site would be frozen in the state that it was when I finished work, and new entries wouldn't automatically abide by the same design rules.
	- (It's written that way so it's easy for anyone to edit, but it makes it hard to figure out how to get past their defaults!)

2. This also applies to the Neatline narrative - I've attached some HTML to the "introduction" section to show you what I mean. Unless you delete the entire thing, you won't eliminate the styling attached; if you do, just copy the HTML setup first so that you can replicate it ("plug and chug").
	- Example of why: I'm wrapping all the narrative sections in their own containers so they can be styled as a set of sections, rather than a giant set of paragraphs and headings.
	- How to see: when you go to `mappinglowell.net/admin/neatline/edit/1` and scroll to **Narrative**, there is a button on the second row of the editor that says `Source`. Click that and you should see the HTML come up!
		- I've labeled the first section and section title with examples of what might be going on "behind the scenes".

3. Header menu: what links would you like there, and in what order? The rule of thumb generally is left-to-right most-to-least important.

<input type="text" id="name" name="name" style="width: 400px;"/> <button id="myBtn">Submit</button>

4. Would you like to keep the same logo that you're currently using, or just use the name of the site instead of a logo? I ask because the colors no longer match, but I can change the color of the orange part of the logo pretty easily.
	- Also: happy to work with you in the future on a logo design, if we decide to keep working together.

5. Any major changes to the website on your end, or questions/comments you have for me? Don't hesitate to email with additional thoughts.

<script type="javascript/text">
	var btn = document.getElementById("myBtn");
	btn.addEventListener("click", function() {

	})
</script>