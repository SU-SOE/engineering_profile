# [SOE Profile](https://github.com/SU-SOE/engineering_profile)

##### 8.x

Maintainers: [Mike Decker](https://github.com/pookmish), [sherakama](https://github.com/sherakama)

Changelog: [Changelog.md](CHANGELOG.md)

## Description

This is the main installation profile for School of Engineering's self service platform. It is similar to SU-SWS/stanford_profile, and shares common parts via the stanford_profile_helper module.

## Accessibility

[![WCAG Conformance 2.0 AA Badge](https://www.w3.org/WAI/wcag2AA-blue.png)](https://www.w3.org/TR/WCAG20/)
This module conforms to level AA WCAG 2.0 standards as required by the university's accessibility policy. For more information on the policy please visit: [https://ucomm.stanford.edu/policies/accessibility-policy.html](https://ucomm.stanford.edu/policies/accessibility-policy.html).

## Installation

Install this installation profile like any other profile. [See Drupal Documentation](https://www.drupal.org/docs/7/install/using-an-installation-profile)

## Configuration

Nothing special needed.

## Releases

Steps to build a new release:

- Checkout the latest commit from the `8.x-1.x` branch.
- Create a new branch for the release.
- Commit any necessary changes to the release branch.
  - These may include, but are not necessarily limited to:
  - Update the version in any `info.yml` files, including in any submodules.
  - Update the CHANGELOG to reflect the changes made in the new release.
- Make a PR to merge your release branch into `main`
- Give the PR a semver-compliant label, e.g., (`patch`, `minor`, `major`). This may happen automatically via Github actions (if a labeler action is configured).
- When the PR is merged to `main`, a new tag will be created automatically, bumping the version by the semver label.
- The github action is built from: [semver-release-action](https://github.com/K-Phoen/semver-release-action), and further documentation is available there.

## Troubleshooting

If you are experiencing issues with this try posting an issue on the [GitHub issues page](https://github.com/SU-SWS/stanford_profile/issues).

## Contribution / Collaboration

You are welcome to contribute functionality, bug fixes, or documentation to this module. If you would like to suggest a fix or new functionality you may add a new issue to the GitHub issue queue or you may fork this repository and submit a pull request. For more help please see [GitHub's article on fork, branch, and pull requests](https://help.github.com/articles/using-pull-requests)

## GitPod

1. Add your ssh key to [GitPod](https://gitpod.io/variables)
1. It is recommended to have a password-less ssh key for simplicity.
1. `ssh-keygen -b 4096`, press enter when asked for the password
1. Add this ssh public key to the necessary services: Acquia, Github, etc.
1. Get the base64 string of your ssh key files
1. `cat id_rsa | base64` for the private key
1. `cat id_rsa.pub | base64` for the public key.
1. In GitPod, add the variable named `SSH_PRIVATE_KEY` with the private key
1. In GitPod, add the variable named `SSH_PUBLIC_KEY` with the public key
1. In Gitpod, add the variable named `GITCONFIG` with the base64 of your git config: `cat ~/.gitconfig | base64`
1. Recommended, but not required:
1. install the GitPod browser plugin
1. Configure your browser settings for an easier experience: https://www.gitpod.io/docs/configure/browser-settings
1. Open a gitpod workspace with [these instructions](https://www.gitpod.io/docs/getting-started#start-your-first-workspace)
