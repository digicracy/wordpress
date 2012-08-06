# Overview

WordPress is a powerful blogging platform written in PHP. This charm aims to deploy WordPress in a fashion that will allow anyone to scale and grow out
a single installation.

# Installation

This charm is available in the Juju Charm Store, to deploy you'll need at a minimum: a cloud environment, a working Juju installation,
and a successful bootstrap. Please refer to the [Juju Getting Started](https://juju.ubuntu.com/docs/getting-started.html) documentation before continuing.

Once bootstrapped, deploy the MySQL charm then this WordPress charm:

    juju deploy mysql
    juju deploy wordpress

Add a relation between the two of them

    juju add-relation wordpress mysql

Expose the WordPress installation

    juju expose wordpress

# Configuration

This WordPress charm comes with several tuning levels designed to encompass the different styles in which this charm will be used.
A use case for each tuning style is outlined below:

## Bare

The Bare configuration option is meant for those who wish to run the stock WordPress setup with no caching, no manipulation of data, 
and no additional scale out features enabled. This is ideal if you intend to install additional plugins to deal with coordinating
WordPress units or simply wish to test drive WordPress as it is out of the box. This will still create a load-balancer when an additional
unit is created, though everything else will be turned off (WordPress caching, APC OpCode caching, and NFS file sharing).

To run this WordPress charm under a bare tuning level execute the following:

    juju set wordpress tuning=bare

## Single

When running in Single mode, this charm will make every attempt to provide a solid base for your WordPress install. By running in single
the following will be enabled: Nginx microcache, APC OpCode caching, WordPress caching module, and a basic NFS mount. While Single mode
is designed to allow for scaling out, it's meant to only scale out for temporary relief; say in the event of a large traffic in-flux.

To run this WordPress charm under a single tuning level execute the following:

    juju set wordpress tuning=single

## Optimized

If you need to run WordPress on more than one instance constantly, or require scaling out and in on a regular basis, then Optimized is the
recommended configuration. When you run WordPress under an Optimized tuning level, the ability to install, edit, and upgrade themes and plugins
is disabled. By doing this the charm can drop the need for an NFS mount which is inefficient and serve everything from it's local disk.
Everything else provided in Single level is available. In order to install or modify plugins with this setup you'll need to edit and commit
them to a forked version of the charm in the files/wordpress/ directory.

To run this WordPress charm under an optimized tuning level execute the following:

    juju set wordpress tuning=optimized

# Caveats


