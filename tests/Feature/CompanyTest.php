<?php

namespace Tests\Feature;

use Src\Agenda\Company\Domain\Factories\AddressFactory;
use Src\Agenda\Company\Domain\Model\ValueObjects\AddressType;
use Src\Agenda\Company\Domain\Model\ValueObjects\ContactRole;
use Src\Agenda\Company\Infrastructure\EloquentModels\AddressEloquentModel;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->company_uri = '/company';
        $this->index_uri = $this->company_uri . '/index';
    }

    /** @test */
    public function admin_can_retrieve_all_companies()
    {
        $companiesCount = $this->faker->numberBetween(1, 10);
        $this->createRandomCompanies($companiesCount);

        $companies = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->index_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($companiesCount + 1); // +1 for the non-admin user

        $companyInfo = $companies->json()[0];
        $this->assertEquals(
            ['id', 'fiscal_name', 'social_name', 'vat', 'main_address', 'num_addresses', 'num_contacts', 'num_departments', 'is_active'],
            array_keys($companyInfo)
        );
    }

    /** @test */
    public function admin_can_get_specific_company_by_id()
    {
        $companiesCount = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($companiesCount);
        $randomCompanyId = $this->faker->randomElement($company_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);
    }

    /** @test */
    public function admin_can_create_a_company()
    {
        $requestBody = [
            'fiscal_name' => $this->faker->name,
            'social_name' => $this->faker->company,
            'vat' => $this->faker->bothify('?#########'),
            'is_active' => $this->faker->boolean,
            'main_address' => AddressFactory::new()->toArray(),
        ];

        $expectedResponse = [
            'fiscal_name' => $requestBody['fiscal_name'],
            'social_name' => $requestBody['social_name'],
            'vat' => $requestBody['vat'],
            'main_address' => $requestBody['main_address'],
            'num_addresses' => 1,
            'num_contacts' => 0,
            'num_departments' => 0,
            'is_active' => $requestBody['is_active']
        ];

        unset($expectedResponse['main_address']['id']);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->company_uri, $requestBody)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($expectedResponse);

        // Assert cannot create company with same vat
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->company_uri, $requestBody)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Vat is already used']);
    }

    /** @test */
    public function cannot_create_company_with_invalid_vat()
    {
        $requestBodyInvalidVat = [
            'fiscal_name' => $this->faker->name,
            'social_name' => $this->faker->company,
            'vat' => 'invalidvat',
            'is_active' => $this->faker->boolean,
            'main_address' => AddressFactory::new()->toArray()
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->company_uri, $requestBodyInvalidVat)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Vat must be valid']);
    }

    /** @test */
    public function admin_can_update_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'main_address', 'social_name', 'vat', 'is_active']);

        $requestBody = [
            'fiscal_name' => $this->faker->name,
            'social_name' => $this->faker->company,
            'vat' => $company->json()['vat'],
            'is_active' => $this->faker->boolean,
            'main_address' => AddressFactory::new()->toArray()
        ];

        $expectedResponse = [
            'id' => $randomCompanyId,
            'fiscal_name' => $requestBody['fiscal_name'],
            'social_name' => $requestBody['social_name'],
            'main_address' => $requestBody['main_address'],
            'vat' => $requestBody['vat'],
            'is_active' => $requestBody['is_active']
        ];

        unset($expectedResponse['addresses'][0]['id']);
        unset($expectedResponse['addresses'][1]['id']);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->company_uri . '/' . $randomCompanyId, $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($expectedResponse);

        $requestBodyInvalidVat = [
            'fiscal_name' => $this->faker->name,
            'social_name' => $this->faker->company,
            'vat' => 'invalidvat',
            'is_active' => $this->faker->boolean,
            'main_address' => AddressFactory::new()->toArray()
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->company_uri . '/' . $randomCompanyId, $requestBodyInvalidVat)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => 'Vat must be valid']);
    }

    /** @test */
    public function admin_can_delete_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->delete($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function cannot_delete_company_if_does_not_exists()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->delete($this->company_uri . '/' . 99999)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_add_an_address_to_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);

        $requestBody = [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement([AddressType::Administrative->value, AddressType::Logistic->value]),
            'street' => $this->faker->streetName(),
            'zip_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country' => $this->faker->countryCode(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->company_uri . '/' . $randomCompanyId . '/address', $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'type', 'street', 'zip_code', 'city', 'country', 'phone', 'email']);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $address = $company->json('addresses')[0];
        unset($address['id']);
        unset($address['company_id']);
        $this->assertEquals($requestBody, $address);
    }

    /** @test */
    public function can_update_an_address_from_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);
        $address = $this->createAddress($randomCompanyId);

        $requestBody = [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement([AddressType::Administrative->value, AddressType::Logistic->value]),
            'street' => $this->faker->streetName(),
            'zip_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country' => $this->faker->countryCode(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->company_uri . '/' . $randomCompanyId . '/address/' . $address->id, $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'type', 'street', 'zip_code', 'city', 'country', 'phone', 'email']);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $address = $company->json('addresses')[0];
        unset($address['id']);
        unset($address['company_id']);
        $this->assertEquals($requestBody, $address);
    }

    /** @test */
    public function can_delete_an_address_from_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);
        $address = $this->createAddress($randomCompanyId);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->delete($this->company_uri . '/' . $randomCompanyId . '/address/' . $address->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $addresses = $company->json('addresses');
        $this->assertEquals([], $addresses);
    }

    /** @test */
    public function can_add_a_department_to_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);
        $addressId = AddressEloquentModel::where('company_id', $randomCompanyId)->first()->id;

        $requestBody = [
            'name' => $this->faker->name,
            'address_id' => $addressId,
            'is_active' => true,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->company_uri . '/' . $randomCompanyId . '/department', $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'name', 'address_id', 'is_active']);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $department = $company->json('departments')[0];
        unset($department['id']);
        unset($department['company_id']);
        $this->assertEquals($requestBody, $department);
    }

    /** @test */
    public function can_update_a_department_from_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);
        $addressId = AddressEloquentModel::where('company_id', $randomCompanyId)->first()->id;
        $department = $this->createDepartment($randomCompanyId, $addressId);

        $requestBody = [
            'name' => $this->faker->name,
            'address_id' => $addressId,
            'is_active' => true,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->company_uri . '/' . $randomCompanyId . '/department/' . $department->id, $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'name', 'address_id', 'is_active']);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $department = $company->json('departments')[0];
        unset($department['id']);
        unset($department['company_id']);
        $this->assertEquals($requestBody, $department);
    }

    /** @test */
    public function can_delete_a_department_from_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);
        $addressId = AddressEloquentModel::where('company_id', $randomCompanyId)->first()->id;
        $department = $this->createDepartment($randomCompanyId, $addressId);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->delete($this->company_uri . '/' . $randomCompanyId . '/department/' . $department->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $departments = $company->json('departments');
        $this->assertEquals([], $departments);
    }

    /** @test */
    public function can_add_a_contact_to_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);

        $requestBody = [
            'contact_role' => $this->faker->randomElement(ContactRole::cases())->value,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'address_id' => null,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->post($this->company_uri . '/' . $randomCompanyId . '/contact', $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'contact_role', 'name', 'email', 'phone', 'address_id']);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $contact = $company->json('contacts')[0];
        unset($contact['id']);
        unset($contact['company_id']);
        $this->assertEquals($requestBody, $contact);
    }

    /** @test */
    public function can_update_a_contact_from_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);
        $contact = $this->createContact($randomCompanyId);

        $requestBody = [
            'contact_role' => $this->faker->randomElement(ContactRole::cases())->value,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'address_id' => null,
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->put($this->company_uri . '/' . $randomCompanyId . '/contact/' . $contact->id, $requestBody)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'contact_role', 'name', 'email', 'phone', 'address_id']);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $contact = $company->json('contacts')[0];
        unset($contact['id']);
        unset($contact['company_id']);
        $this->assertEquals($requestBody, $contact);
    }

    /** @test */
    public function can_delete_a_contact_from_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);
        $contact = $this->createContact($randomCompanyId);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->delete($this->company_uri . '/' . $randomCompanyId . '/contact/' . $contact->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $company = $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'addresses', 'contacts', 'departments', 'is_active']);

        $contacts = $company->json('contacts');
        $this->assertEquals([], $contacts);
    }

    // User Tests
    /** @test */
    public function user_cannot_retrieve_all_companies()
    {
        $companiesCount = $this->faker->numberBetween(1, 10);
        $this->createRandomCompanies($companiesCount);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->get($this->index_uri)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }

    /** @test */
    public function user_cannot_get_specific_company_by_id_except_if_belongs_to()
    {
        $companiesCount = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($companiesCount);
        $randomCompanyId = $this->faker->randomElement($company_ids);

        // User cannot retrieve company where it is not belonged to
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);

        // User can retrieve company where it belongs
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->get($this->company_uri . '/' . $this->userData['company_id'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'is_active']);
    }

    /** @test */
    public function user_cannot_create_a_company()
    {
        $requestBody = [
            'fiscal_name' => $this->faker->name,
            'social_name' => $this->faker->company,
            'vat' => $this->faker->bothify('?#########'),
            'is_active' => $this->faker->boolean,
            'main_address' => AddressFactory::new()->toArray()
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->post($this->company_uri, $requestBody)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }

    /** @test */
    public function user_cannot_update_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomElement($company_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->adminToken])
            ->get($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['id', 'fiscal_name', 'social_name', 'vat', 'is_active']);

        $requestBody = [
            'fiscal_name' => $this->faker->name,
            'social_name' => $this->faker->company,
            'vat' => $this->faker->bothify('?#########'),
            'is_active' => $this->faker->boolean,
            'main_address' => AddressFactory::new()->toArray()
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->put($this->company_uri . '/' . $randomCompanyId, $requestBody)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }

    /** @test */
    public function user_cannot_delete_a_company()
    {
        $numberCompanies = $this->faker->numberBetween(1, 10);
        $company_ids = $this->createRandomCompanies($numberCompanies);
        $randomCompanyId = $this->faker->randomELement($company_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->userToken])
            ->delete($this->company_uri . '/' . $randomCompanyId)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['error' => 'The user is not authorized to access this resource or perform this action']);
    }
}
