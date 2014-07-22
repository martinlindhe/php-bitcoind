<?php

class ConnectionRefusedException extends \Exception
{
}

class ConnectionErrorException extends \Exception
{
}

/**
 * Is thrown if user credentials is incorrect
 */
class AuthenticationFailureException extends \Exception
{
}

/**
 * Is thrown if user tries to call a non-existing RPC method
 */
class MethodNotFoundException extends \Exception
{
}

/**
 * Is thrown on generic failure (ie server dont respond HTTP 200 OK)
 */
class NotOkayException extends \Exception
{
}
