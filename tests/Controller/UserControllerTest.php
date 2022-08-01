
declare(strict_types=1);

namespace SimpleMVC\Test\Controller;

use App\Controller\Secret;
use App\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserControllerTest extends TestCase
{
   
    /** @var ServerRequestInterface|MockObject */
    private $request;

    /** @var ResponseInterface|MockObject */
    private $response;

    //User controllers
    private GetUserPublicController $userController;

    private UserService $userService;

    /** @var string[] */
    private array $auth;

    public function setUp(): void
    {
        $this->auth = [
            'username' => 'test', 
            'password' => 'password'
        ];
        $this->plates = new Engine(__DIR__ . '/../../src/View');
        $this->secret = new Secret($this->plates, $this->auth);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testExecuteReturn200(): void
    {
        $response = $this->secret->execute($this->request, $this->response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testExecuteBodyHasUsernameAndPassword(): void
    {
        $response = $this->secret->execute($this->request, $this->response);
        $this->assertEquals(
            $this->plates->render('secret', $this->auth), 
            (string) $response->getBody()
        );
    }
}
