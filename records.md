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