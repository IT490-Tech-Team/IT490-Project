# Idea

3 Repos

1. Prod
2. QA
3. Dev

features go up the ladder.


## Branches

For this moment onward, main will be completely protected and can only be commited into through a PR. Each new PR acceptance\denial will trigger a new PR\Issue in the next\prev repo. Each new PR/Issue will be given a new branch to work on. 

An issue is something someone must work on.
A PR is a submission to the next repo. 
Each new PR acceptance will trigger a new branch and PR in the next repo.
Every denial creates a new issue in dev, where the dev must work on the changes required, then create a new PR to go up the ladder.

## Branch closing

This system is based on branches and PRs with (labels and version numbers). 
Drafts don't need labels, but full PRs do. 

To close would be to commit the changes from 1 repo to another
1. Dev PR accept → Requires 1 reviewer → Test (new branch + PR)
2. Test PR accept → requires 2 reviewers → Prod (new branch + PR)
3. Prod PR accept → requires 3 reviewers → Prod (main)

1. Dev PR deny → New Dev Issue + Branch
1. Test PR deny → New Dev Issue + Branch
1. Prod PR deny → New Dev Issue + Branch

Every PR accept, a github action will play out where it creates an installable deb
This deb will take the changes from a PR, bundle them together, and based on the changes figure out what scripts it has to run.
It will then create a new release in that repo.

## Labels

Each label represents a feature i.e. login, registration, etc. etc.

## Issues

Say for example the reviewer denies the pull request made. A new issue will be created in the previous repo, with an explanation of why it was denied.
The previous dev must then work to close that issue, and make a new PR, with the same label.

The next PR will need the same label but a different version, and the process continues.


## Releases & VMs

as mentioned earlier, each release will be a bundle of changes, which runs an assortment of clips
When ever a VM from a particular stage boots up, it will check the releases from the previous stage. and install it.

i.e. 
- test checks for releases in dev
- prod checks for releases in test

There will be assigned testers for each branch (no dev tester). The tester(s) will have the sole permission to close PRs in a specific repo.
1 is assigned for test
2 are assigned for prod