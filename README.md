# Yet another Laravel DDD interpretation

## Introduction
I want to share with you yet another Laravel DDD interpretation, my approach for what could be a clean architecture design without having to give away most of the features we love from Laravel.

Some of my inspirations have been these remarkable articles:
- https://www.hibit.dev/posts/43/domain-driven-design-with-laravel-9
- https://lorisleiva.com/conciliating-laravel-and-ddd
- https://ntorga.com/the-presentation-layer-clean-architecture-and-domain-driven-design-on-php/
- https://ntorga.com/the-domain-layer-clean-architecture-and-domain-driven-design-on-php/

First, I want to say that this is my own personal interpretation, which is also very open to suggestions and opinions. One of the things I have learned along this time is that what we call clean architectures, DDD, hexagonal architectures, etc. is interpreted differently from author to author although most of them refer to Uncle Bob, Martin Fowler, Eric Evans and Vaughn Vernon as the most influential on this topic. So, if you want to delve into this topic, I recommend reading about them.

## Current features
- Authentication with [Tymon's JWT Auth](https://github.com/tymondesigns/jwt-auth)
- Feature tests for User and Auth

## First steps
1. ```composer install```
2. ```php artisan key:generate```
3. ```php artisan jwt:secret```
4. ```php artisan test```
5. For new domains, use this command: ```php artisan make:domain {Bounded Context} {Domain}``` (e.g. ```php artisan make:domain Blog Post```)

## Why use this approach?

Okay, let’s suppose that you want to program an app with Laravel that you expect to be mid-to-large size. You may have been working on some of these big projects but dealt with bloated controllers, monstrous models, etc. So for this one, you want to keep your sanity.

You hear about clean architecture and would like to try it, but its practices kind of break with the Laravel Way™ of building things, so either you have to stick with Laravel or create almost all of the core functionalities with that permanent feeling of reinventing the wheel at every step.

I don’t have a perfect solution for this, and I haven’t heard of anyone having it, but I found a way that allows me to build things by having a more controlled planning and structure of my project despite having to deal with some extra boilerplate.

## Structure particularities

Inside the "src/" directory we will keep our Bounded Contexts, which are the delimitations of around a set of domains that share functionalities and the same ubiquitous language. There is a special Bounded Context that I have named “Common”, where I keep the resources that I will be sharing with many domains, and where I will keep our Laravel-specific logic as an infrastructure detail.

> <sub>By the way, the "src/" directory is somewhat the "app/" directory we are used to in Laravel. I changed its name because I do not feel comfortable working with a folder that shares the name of a conventional Laravel app when this is not.</sub>

Also, I prefer to group the directory structure by domain, contrary to many examples I saw where some authors prefer grouping by layer. For example, a typical structure would be:

```
...
├── Domain
│   ├── User
│   └── Post
├── Application
│   ├── UserRepository
│   ├── PostRepository
│   └── ...
├── Interfaces
│   ├── UserController
│   ├── PostController
│   └── ...
├── Infrastructure
│   ├── UserEloquent
│   ├── PostEloquent
│   └── ...
```

While I prefer:

```
...
├── User
│   ├── Domain
│   ├── Application
│   ├── Interfaces
│   └── Infrastructure
├── Post
│   ├── Domain
│   ├── Application
│   ├── Interfaces
│   └── Infrastructure
...
```

I find it cleaner this way, although it may have repeated directories, I think it is more readable and is better for large applications.

Another aspect of this approach is that we have to break with the MVC pattern. Our controllers will be at the Interface layer (also called Presentation), directly attending to the requests and passing the data to an inner layer, receiving a response and giving it back to the client. The views can be rendered from the controller, as always, although I prefer using a separated frontend app (like Vue, React, Svelte, etc).

This approach also needs to define a specific ServiceProvider for each domain to bind out abstractions to the implementations. These providers will need to be registered in the "config/app.php" file in order to work.

Finally, what we consider a model in Laravel has been changed to what I called an EloquentModel, which will be an infrastructure detail. This is because Laravel uses an active record pattern for its models, and we want our models to be decoupled from the database, so we cannot put them in the domain. We can still benefit from Eloquent and other features using it inside the Application/Repository/Eloquent implementation while keeping it out from our domain.

## Other considerations

As stated before, this repository has been created and made public for anyone who can find it practical and inspiring. It is a very early concept, so any contributions to improve it are more than welcome.
