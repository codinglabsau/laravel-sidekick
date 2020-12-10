## Overview
A common open source composer dev dependency called codinglabs/sidekick that provides some artisan commands to help scaffold some common files to our Laravel projects
- auto-assign a reviewer when a PR is opened
- setup GitHub actions
- keep logic in sync across projects

## Implementation
### Codeowners
`php artisan make:codeowners --team=[reviewers]`
- Scaffolds a ./github/CODEOWNERS file from a stub
- Accepts CLI options, with the default reviewer should be the Reviewers team.
- Add confirmation when overriding an existing file.

https://github.com/marketplace/actions/auto-assign-review-teams  
https://docs.github.com/en/free-pro-team@latest/github/creating-cloning-and-archiving-repositories/about-code-owners#codeowners-syntax

### Review Template
`php artisan make:review-templates`
- Scaffolds a PR description review template
    - The template should provide a structure the owner to complete the description.
    - The template should also have a checklist for the owner, and a seperate one for the reviewer
    - Add confirmation when overriding an existing file.

[Guide: GitHub 🕵️](https://3.basecamp.com/4152151/buckets/10399046/documents/3224448185)   
https://docs.github.com/en/free-pro-team@latest/github/building-a-strong-community/creating-a-pull-request-template-for-your-repository

### Coding Styles
`php artisan make:coding-styles`
- Scaffolds the https://github.com/codinglabsau/php-styles .php_cs.dist file to the root of the project and runs composer require codinglabsau/php-styles --dev.

#### Idea
- Have a stubs/.php_cs.dist template, and show the contents of this file inline in the php-styles repo README - and after requiring the package, copy the stub to the current project from the vendor directory.
- Command triggers a dry run of code fixes after install: `./vendor/bin/php-cs-fixer fix --dry-run`
