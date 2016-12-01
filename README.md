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

From `bash`, try running `php`. If this succeeds, great! Otherwise, run `sudo apt-get install php5-cli` or whatever its suggestion is.

You can find your C drive at `/mnt/c`. From there you can `cd` to the project's directory.

### Installing Composer

Composer is the de facto package manager for PHP. To get it, `cd` into this project's directory, then go to https://getcomposer.org/download/ and follow the instructions there.

Once installed, run the following:

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

### No, I got nasty MySQL authentication errors!

Open wiebe and log into mysql. Run the following:

```
SET SESSION old_passwords=FALSE;
SET PASSWORD = PASSWORD('[your password]');
```

`[your password]` should of course be replaced with your actual password.
