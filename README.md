# buckthorn

## Getting Started

### Installing PHP

#### OS X / macOS

Make sure you have a command-line version of PHP 5 installed. You should be able to run `php --version` to check this. If no version of PHP is installed, you can run the following:

```
brew update
brew install php56
```

Of course, if *this* fails, you need to install [Homebrew](http://brew.sh/).

#### Windows 10

First install the [Windows Subsystem for Linux](https://msdn.microsoft.com/en-us/commandline/wsl/install_guide).

From `bash`, try running `php`. If this succeeds, great! Otherwise, run the following:

```
sudo apt-get install php5-cli
sudo apt-get install php5-mysql
sudo apt-get install php5-mysqlnd
```

You can find your C drive at `/mnt/c`. From there you can `cd` to the project's directory.

### Installing Composer

Composer is the de facto package manager for PHP. To get it, `cd` into this project's directory, then go to https://getcomposer.org/download/ and follow the instructions there. **Make sure to use the command-line installation.**

Once Composer is installed, run the following:

```
php composer.phar install
```

### Configuring your .env file

First make a copy of `.example.env` named `.env`:

```
cp .example.env .env
```

Edit the contents of this file to include your mysql username and password.

### Starting the server

You will need two terminal windows open to run this project: one to run the SSH tunnel, and the other to run the PHP server.

In the first terminal window, run the following:

```
./tunnel.sh
```

In the other, run the following:

```
./serve.sh
```

You should now be able to open `http://localhost:8000` in your browser and see a success message!

### I got nasty MySQL authentication errors!

Open wiebe and log into mysql. Run the following:

```
SET SESSION old_passwords=FALSE;
SET PASSWORD = PASSWORD('[your password]');
```

`[your password]` should of course be replaced with your actual password.


## Project Structure

The project structure is quite simple. All PHP files that are accessible through the browser are in the root folder. The other folders are as follows:

- `assets` contains static assets like stylesheets and images.
- `templates` contains `header.php` and `footer.php`, which are included in most PHP files to ensure that they look pretty in the browser.
- `utilities` contains several files with helpful functions. Each file is thoroughly commented.
