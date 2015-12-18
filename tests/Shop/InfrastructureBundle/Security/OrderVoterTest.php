<?php

namespace Tests\Shop\InfrastructureBundle\Security;

use Shop\Domain\Order\OrderView;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Security\OrderVoter;
use Shop\InfrastructureBundle\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class OrderVoterTest extends \PHPUnit_Framework_TestCase
{
    public function testVoteGrantedWithSameIds()
    {
        $token = $this->getTokenMock();
        $orderView = $this->getMock(OrderView::class, ['getCustomerId']);
        $orderView->expects($this->once())->method('getCustomerId')->willReturn('xxx');
        $voter = new OrderVoter();
        $this->assertSame(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $orderView, ['any']));
    }

    public function testDeniedWithDifferentIds()
    {
        $token = $this->getTokenMock();
        $orderView = $this->getMock(OrderView::class, ['getCustomerId']);
        $orderView->expects($this->once())->method('getCustomerId')->willReturn('yyy');
        $voter = new OrderVoter();
        $this->assertSame(VoterInterface::ACCESS_DENIED, $voter->vote($token, $orderView, ['any']));
    }

    /**
     * @return TokenInterface
     */
    protected function getTokenMock()
    {
        $uuid = new UuidIdentity('xxx');
        $user = $this->getMock(User::class, ['getId'], [], '', false);
        $user->expects($this->once())->method('getId')->willReturn($uuid);
        $token = $this->getMock(TokenInterface::class, []);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        return $token;
    }
}