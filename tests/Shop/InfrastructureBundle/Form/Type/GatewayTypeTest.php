<?php

namespace Tests\Shop\InfrastructureBundle\Form\Type;

use Payum\Bundle\PayumBundle\Registry\ContainerAwareRegistry;
use Shop\InfrastructureBundle\Form\GatewayExtension;
use Shop\InfrastructureBundle\Form\Type\GatewayType;
use Symfony\Component\Form\Test\TypeTestCase;

class GatewayTypeTest extends TypeTestCase
{
    private static $gateways = [];

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$gateways = [
            'offline' => new \stdClass,
            'paypal' => new \stdClass,
        ];
    }

    public function testChoices()
    {
        $form = $this->factory->create(GatewayType::class);
        $choices = $form->createView()->vars['choices'];
        foreach (array_keys(self::$gateways) as $key => $name) {
            $this->assertAttributeSame('form.choice.'.$name, 'label', $choices[$key]);
            $this->assertAttributeSame($name, 'value', $choices[$key]);
        }
    }

    protected function getExtensions()
    {
        /** @var ContainerAwareRegistry|\PHPUnit_Framework_MockObject_MockObject $registry */
        $registry = $this->getMockBuilder(ContainerAwareRegistry::class)
            ->setMethods(['getGateways'])
            ->disableOriginalConstructor()
            ->getMock();
        $registry->expects($this->once())->method('getGateways')->willReturn(self::$gateways);

        return array_merge(
            parent::getExtensions(),
            [new GatewayExtension($registry)]
        );
    }
}