roblox-messages
===============

roblox-messages is a small but powerful PHP class that allows you to manage your private messages on ROBLOX.

## Usage
1. Clone this repo into your project's directory.
2. Create a new file and include the messages.class.php file located in src/
3. Write some code.

## Examples

Before you do anything, you need to set the .ROBLOSECURITY cookie. Extract it from your browser and save the contents in a readable file.



##### Setting the .ROBLOSECURITY cookie

```php
$roblox_messages->set_cookie('roblosecurity.txt');
```


##### Retrieving your message count

``` php
$roblox_messages->set_cookie('roblosecurity.txt');
$count = $roblox_messages->get_message_count();
```


##### Retrieving your messages

```php
$roblox_messages->set_cookie('roblosecurity.txt');
$messages = $roblox_messages->get_raw_messages(); // Retrieve all messages
$limited_messages = $roblox_messages->get_raw_messages(10); // Retrieve the 10 most recent messages
```

## Current features

1. Retrieve message count.
2. Retrieve all messages.


## Upcoming features

1. Formatted message extraction (JSON, XML)
2. Filtering options
3. Clear all messages
