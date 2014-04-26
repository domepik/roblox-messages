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
$all_messages = $roblox_messages->get_raw_messages(); // Retrieve all messages
$limited_messages = $roblox_messages->get_raw_messages(10); // Retrieve the 10 most recent messages
```


##### Formatting your messages

```php
$roblox_messages->set_cookie('roblosecurity.txt');
$messages = $roblox_messages->get_raw_messages(); // Retrieve all messages
$formatted = $roblox_messages->get_json_messages($messages);
```

If there is no argument in get_json_messages, it will send the request to getmessages. This function doesn't however support a message limit, meaning you extract it all or nothing. If you want to get limited messages, use the first example.

```php
$roblox_messages->set_cookie('roblosecurity.txt');
$formatted = $roblox_messages->get_json_messages();
```

If you would like to return the data as an object instead of a string, you can always use json_decode().

```php
$roblox_messages->set_cookie('roblosecurity.txt');
$str = $roblox_messages->get_json_messages();
$obj = json_decode($str, FALSE);
```


## Current features

1. Retrieve message count.
2. Retrieve all messages.
3. JSON formatting


## Upcoming features

1. Filtering options.
2. Inbox clearing.
3. Mass responding.
