<?php declare(strict_types=1);

namespace ApiClients\Tests\Client\Pusher\CommandBus\Handler;

use ApiClients\Client\Pusher\AsyncClient;
use ApiClients\Client\Pusher\CommandBus\Command\SharedAppClientCommand;
use ApiClients\Client\Pusher\CommandBus\Handler\SharedAppClientHandler;
use ApiClients\Client\Pusher\Service\SharedAppClientService;
use ApiClients\Tools\TestUtilities\TestCase;
use function Clue\React\Block\await;
use function EventLoop\getLoop;

final class SharedAppClientHandlerTest extends TestCase
{
    public function testHandle()
    {
        $loop = getLoop();
        $appId = uniqid('app-id-', true);
        $handler = new SharedAppClientHandler(new SharedAppClientService($loop));

        $app = await($handler->handle(new SharedAppClientCommand($appId)), $loop);
        self::assertInstanceOf(AsyncClient::class, $app);
        self::assertSame($app, await($handler->handle(new SharedAppClientCommand($appId)), $loop));
        self::assertNotSame($app, await($handler->handle(new SharedAppClientCommand(md5($appId))), $loop));
    }
}
