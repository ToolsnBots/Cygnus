cp Password.example.php Password.php
phpunit ./tests/BotCoreReadTest.php $readSite $readUser $readPwd
phpunit ./tests/BotCoreReadAssertFailTest.php $AssertWrongSite $AssertWrongUser $AssertWrongPwd