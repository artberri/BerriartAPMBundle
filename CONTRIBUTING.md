# Contributing

## Code of Conduct

The Code of Conduct explains the *bare minimum* behavior
expectations required for contributors. We don't have time to write
one, so just follow the Twitter one.
[Please read it before participating.](https://github.com/twitter/code-of-conduct/blob/master/code-of-conduct.md)

## Issue Contributions

When opening new issues or commenting on existing issues on this repository
please make sure discussions are related to concrete technical issues with the
BerriartAPMBundle software.

## Code Contributions

The BerriartAPMBundle project has an open governance model and welcomes new contributors.
This document will guide you through the contribution process.

### Step 1: Fork

Fork the project [on GitHub](https://github.com/artberri/BerriartAPMBundle) and check out your
copy locally.

```text
$ git clone git@github.com:username/BerriartAPMBundle.git
$ cd BerriartAPMBundle
$ git remote add upstream git://github.com/artberri/BerriartAPMBundle.git
```

#### Which branch?

For developing new features and bug fixes, the `master` branch should be pulled
and built upon.

#### Dependencies

BerriartAPMBundle has several bundled dependencies in the *vendor/*
directory that are not part of the project proper. Any changes to files
in those directories or its subdirectories should be sent to their respective
projects. Do not send your patch to us, we cannot accept it.

In case of doubt, open an issue in the
[issue tracker](https://github.com/artberri/BerriartAPMBundle/issues/) or contact one of the
[project Collaborators](https://github.com/artberri/BerriartAPMBundle/#current-project-team-members).
Especially do so if you plan to work on something big. Nothing is more
frustrating than seeing your hard work go to waste because your vision
does not align with the project team.

### Step 2: Branch

Create a branch and start hacking:

```text
$ git checkout -b my-branch -t origin/master
```

### Step 3: Commit

Make sure git knows your name and email address:

```text
$ git config user.name "J. Random User"
$ git config user.email "j.random.user@example.com"
```

Writing good commit logs is important. A commit log should describe what
changed and why. Follow these guidelines when writing one:

1. The first line should be 50 characters or less and contain a short
   description of the change prefixed with the name of the changed
   subsystem.
2. Keep the second line blank.
3. Wrap all other lines at 72 columns.

A good commit log can look something like this:

The header line should be meaningful; it is what other people see when they
run `git shortlog` or `git log --oneline`.

Check the output of `git log --oneline files_that_you_changed` to find out
what subsystem (or subsystems) your changes touch.

If your patch fixes an open issue, you can add a reference to it at the end
of the log. Use the `Fixes:` prefix and the full issue URL. For example:

```txt
Fixes: https://github.com/artberri/BerriartAPMBundle/issues/13
```

### Step 4: Rebase

Use `git rebase` (not `git merge`) to sync your work from time to time.

```text
$ git fetch upstream
$ git rebase upstream/master
```

### Step 5: Test

The project has not any test jet, but you should pass some minimum quality
checks by running:

```text
$ composer install
$ ./vendor/phpmd/phpmd/src/bin/phpmd ./ text  phpmd.xml --exclude vendor
$ ./vendor/bin/phpcs --config-set installed_paths vendor/escapestudios/symfony2-coding-standard
$ ./vendor/bin/phpcs
$ ./vendor/bin/phpcpd ./ --exclude=vendor
```

### Step 6: Push

```text
$ git push origin my-branch
```

Go to https://github.com/yourusername/BerriartAPMBundle and select your branch.
Click the 'Pull Request' button and fill out the form.

Pull requests are usually reviewed within a few days or a couple of weeks. If there are comments
to address, apply your changes in a separate commit and push that to your
branch. Post a comment in the pull request afterwards; GitHub does
not send out notifications when you add commits.

<a id="developers-certificate-of-origin"></a>
## Developer's Certificate of Origin 1.1

By making a contribution to this project, I certify that:

* (a) The contribution was created in whole or in part by me and I
  have the right to submit it under the open source license
  indicated in the file; or

* (b) The contribution is based upon previous work that, to the best
  of my knowledge, is covered under an appropriate open source
  license and I have the right under that license to submit that
  work with modifications, whether created in whole or in part
  by me, under the same open source license (unless I am
  permitted to submit under a different license), as indicated
  in the file; or

* (c) The contribution was provided directly to me by some other
  person who certified (a), (b) or (c) and I have not modified
  it.

* (d) I understand and agree that this project and the contribution
  are public and that a record of the contribution (including all
  personal information I submit with it, including my sign-off) is
  maintained indefinitely and may be redistributed consistent with
  this project or the open source license(s) involved.
