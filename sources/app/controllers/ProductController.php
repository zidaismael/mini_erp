<?php
declare(strict_types = 1);

use Exception\ApiException;
use Exception\DuplicateModelException;
use Exception\ForeignKeyModelException;

class ProductController extends AbstractController
{

    /**
     * Get Product list or one product
     *
     * @param int $id
     *            default null, if null => list else get one
     * @return \Phalcon\Http\Response
     */
    public function get($id = null)
    {
        if (is_null($id)) { // no id passed => list
            $result = ProductModel::find();
            $product = $result->toArray();
        } else { // id passed => get one
                 // input parameters validation
            $this->validateData('int', $id, [
                'message' => "Id must be an integer."
            ]);
            
            $product = ProductModel::findFirst($id);
        }
        
        if (! empty($product)) { // result in DB
            return $this->output(200, $product);
        } else { // no result
            if (is_null($id)) {
                return $this->output(200, []);
            } else {
                throw new ApiException("Not found",404);
            }
        }
    }

    /**
     * Create Product
     *
     * @return \Phalcon\Http\Response
     */
    public function post()
    {
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'name',
            'price',
            'tax'
        ]);
        
        // must have one product owner
        if (! array_key_exists('company_id', $body) && ! array_key_exists('provider_id', $body) 
            || array_key_exists('company_id', $body) && array_key_exists('provider_id', $body)) {
            throw new \Exception("At least one (and only one) field must be provide: company_id or provider_id", 400);
        }

        $this->validateMandatoryInput($body);
        
        $product = new ProductModel();
        $this->setReference($product);
        $product->assign($body);
        
        try {
            if ($product->create()) {
                $result = $product->findFirst($product->id);
                return $this->output(201, $result);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on product creation: %s", json_encode($body)));
            }
        } catch (ForeignKeyModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("One or more object's relations can't be create. Please check your object values.", json_encode($body)), 400);
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on product creation: %s", json_encode($body)));
        }
    }

    /**
     * Update Product
     *
     * @param int $id            
     */
    public function put(int $id)
    {
        
        // get body and check mandatory input parameters validation
        $body = $this->getBody([
            'name',
            'price',
            'tax',
            'stock'
        ]);
        
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $this->validateMandatoryInput($body);
        
        // check if exists
        $product = ProductModel::findFirst($id);
        
        if (empty($product)) {
            throw new ApiException("Not found",404);
        }
        
        $product->assign($body);
        
        try {
            if ($product->save()) {
                $product = $product->findFirst($product->id);
                return $this->output(200, $product);
            } else {
                // @todo log
                throw new ApiException(sprintf("An error occured on product update: %s", json_encode($body)));
            }
        } catch (ForeignKeyeModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 400);
        } catch (DuplicateModelException $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("Unique information have been duplicate: %s \nbody: %s", $e->getSubject(), json_encode($body)), 409);
        } catch (\Exception $e) {
            // @todo log
            var_dump($e->getMessage());
            throw new ApiException(sprintf("An error occured on product creation: %s", json_encode($body)));
        }
    }

    /**
     * Delete Product
     *
     * @param int $id            
     */
    public function delete(int $id)
    {
        // input parameters validation
        $this->validateData('int', $id, [
            'message' => "Id must be an integer."
        ]);
        
        $product = ProductModel::findFirst($id);
        
        if (empty($product)) {
            throw new ApiException("Not found",404);
        } else {
            $product->delete();
            return $this->output(204);
        }
    }

    /**
     * Validate input
     *
     * @param array $body            
     */
    protected function validateMandatoryInput(array $body)
    {
        // validate input field values
        $this->validateData('string', $body['name'], [
            'min' => 2,
            'max' => 100,
            'messageMaximum' => "Product's name must be a string between 2 and 100 characters",
            'includedMinimum' => false,
            'includedMaximum' => false
        ]);
        
        $this->validateData('numeric', $body['price'], [
            'message' => "Price must be numerical"
        ]);
        
        $this->validateData('numeric', $body['tax'], [
            'message' => "Tax must be numerical"
        ]);
        
        if (array_key_exists('stock', $body)) {
            $this->validateData('int', $body['stock'], [
                'message' => "Stock must be an integer"
            ]);
        }
        
        if (array_key_exists('company_id', $body)) {
            $this->validateData('int', $body['company_id'], [
                'message' => "Invalid company id"
            ]);
        }
        
        if (array_key_exists('provider_id', $body)) {
            $this->validateData('int', $body['provider_id'], [
                'message' => "Invalid provider id"
            ]);
        }
    }

    /**
     * Auto set reference
     *
     * @param \Phalcon\Mvc\Model $product            
     */
    protected function setReference(\Phalcon\Mvc\Model $product)
    {
        $prefix= isset($product->company_id) ? "PRD" : "PRD_PRV";
        
        $product->reference = sprintf("%s_%d",$prefix, random_int(0, 999999999));
    }
}

