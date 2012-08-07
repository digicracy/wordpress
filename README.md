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
the following will be enabled: Nginx microcache, APC OpCode caching, WordPress caching module, and the ability to sync files via NFS.
While Single mode is designed to allow for scaling out, it's meant to only scale out for temporary relief; say in the event of a large
traffic in-flux. It's recommended for long running scaled out versions that optimized is used. The removal of the file share speeds up
the site and servers ensuring that the most efficient set up is provided. 

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

## Single mode and the scale-out

If you're in Single mode and you want to/need to scale out, but you've been upgrading, modifying, and installing plugins + themes like
a normal WordPress user on a normal install; you can still scale out but you'll need to deploy a shared-fs charm first. At the time of
this writing only the NFS charm will work, but as more shared-fs charms come out (gluster, ceph, etc) that provide a shared-fs/mount 
interface those should all work as well. In this example we'll use NFS:

    juju deploy nfs
    juju add-relation nfs wordpress

By doing so, everything in the wp-contents directory is moved to this NFS mount and then shared to all future WordPress units. It's strongly
recommended that you first deploy the nfs mount, _then_ scale WordPress out. Failure to do so may result in data loss. Once nfs is deployed, 
running, and related you can scale out the WordPress unit using the following command:

    juju add-unit wordpress
    
In the event you want more than one unit at a time (and do not wish to run the add-unit command multiple times) you can supply a `-n` number
of units to add, so to add three more units:

    juju add-unit -n3 wordpress
    
## I don't want to run three different machines for one WP install

There is a "hack" that will allow you to deploy multiple full services to the same machine as the bootstrap node, this has nothing to do with
the charm, but it's something that comes up more than once. Use this, of course, at your own risk. At any time the Juju developers may smart
up and decide to remove this configuration option from the `environments.yaml` file. Prior to your first deployment you'll need to add the
following line to your Juju Environments file:

    placement: local

This will say "Everything that you deploy, will go on the bootstrap node". Make sure you plan to have a big enough bootstrap node to house
both your database and WordPress install. After you've bootstrap'd the environment, deploy the MySQL and WordPress charms like you normally
would. Instead of seeing three nodes you'll only see one, but both of your services will have been deployed. *FROM THIS POINT* you should
either remove or comment out the `placement` line in the environments file. This will prevent issues from occurring when you try to deploy
additional services or try to scale out existing services.
