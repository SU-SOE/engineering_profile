# Upstream updates

This repo is based on the `SU-SOE/soe_profile` repo with extensive customizations for the Engineering main school site.

We have a `config_split` set up to hold most of the customizations for the school site, so it's reasonably straightforward to perform upstream updates.

1) Pull down a clean copy of this repo
2) Create a new branch from the base branch (currently 12.x)
3) `git pull https://github.com/SU-SOE/soe_profile.git tags/11.3.8 -X ours --no-edit --no-commit` adjust for the most recent tag on soe_profile
4)  You now have all the changes, and very likely some merge conflicts.  Go through the changed files (I suggest using the source control view in vs code), and make sure the updates make sense.  Pay particular attention to configs and namespaces.
5) Once you have the branch cleaned up, commit and push. open a PR
6) Track if tests pass.  If they do not, figure out why, and correct any issues.  Remember, there are differences for this profile which mean that many of the tests in the `soe_profile` are not relevant and can be commented out.

Then, update the stack repo to reference your branch here.  Look through the site and ensure that all functionality (especially any custom functionality) is working correctly.


