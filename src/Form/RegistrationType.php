<?php
/**
 * Registration type.
 */

namespace App\Form;

use App\Entity\User;
use App\Entity\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType.
 */
class RegistrationType extends AbstractType
{
    /**
     * Builds form.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder Form builder interface
     * @param array $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'nickname',
            TextType::class,
            [
                'label' => 'label_nickname_star',
                'required' => true,
                'attr' => ['max_length' => 50],
            ]
        );
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label_name',
                'required' => false,
                'attr' => ['max_length' => 30],
            ]
        );
        $builder->add(
            'surname',
            TextType::class,
            [
                'label' => 'label_surname',
                'required' => false,
                'attr' => ['max_length' => 45],
            ]
        );
        $builder->add(
            'user', UserType::class, [
                'label' => 'label_access_data',
                'required' => true,
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
	[
		'data_class' => UserData::class,
		'validation_groups' => ['password'],
	]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'registration';
    }
}