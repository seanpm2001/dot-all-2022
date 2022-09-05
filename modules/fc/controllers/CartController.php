<?php

namespace modules\fc\controllers;

use Craft;
use craft\commerce\elements\Variant;
use craft\commerce\elements\Order;
use craft\commerce\controllers\CartController as Commerce_CartController;

class CartController extends Commerce_CartController
{
	protected function cartArray(Order $cart): array
	{
		$data = parent::cartArray($cart);

		// Create a line items array and loop through the line items in the cart to populate it
		$lineItems = [];
		foreach ($cart->lineItems as $lineItem) {
			// Replicate all the current data we get from line items
			$lineItemData['adjustments'] = $lineItem->adjustments;
			$lineItemData['adjustmentsTotalAsCurrency'] = $lineItem->adjustmentsTotalAsCurrency;
			$lineItemData['dateCreated'] = $lineItem->dateCreated;
			$lineItemData['dateUpdated'] = $lineItem->dateUpdated;
			$lineItemData['description'] = $lineItem->getDescription();
			$lineItemData['discountAsCurrency'] = $lineItem->discountAsCurrency;
			$lineItemData['height'] = $lineItem->height;
			$lineItemData['id'] = $lineItem->id;
			$lineItemData['length'] = $lineItem->length;
			$lineItemData['lineItemStatusId'] = $lineItem->lineItemStatusId;
			$lineItemData['note'] = $lineItem->note;
			$lineItemData['onSale'] = $lineItem->onSale;
			$lineItemData['options'] = $lineItem->options;
			$lineItemData['optionsSignature'] = $lineItem->optionsSignature;
			$lineItemData['orderId'] = $lineItem->orderId;
			$lineItemData['price'] = $lineItem->price;
			$lineItemData['priceAsCurrency'] = $lineItem->priceAsCurrency;
			$lineItemData['privateNote'] = $lineItem->privateNote;
			$lineItemData['purchasableId'] = $lineItem->purchasableId;
			$lineItemData['qty'] = $lineItem->qty;
			$lineItemData['saleAmount'] = $lineItem->saleAmount;
			$lineItemData['saleAmountAsCurrency'] = $lineItem->saleAmountAsCurrency;
			$lineItemData['salePrice'] = $lineItem->salePrice;
			$lineItemData['salePriceAsCurrency'] = $lineItem->salePriceAsCurrency;
			$lineItemData['shippingCategoryId'] = $lineItem->shippingCategoryId;
			$lineItemData['shippingCost'] = $lineItem->shippingCost;
			$lineItemData['shippingCostAsCurrency'] = $lineItem->shippingCostAsCurrency;
			$lineItemData['sku'] = $lineItem->getSku();
			$lineItemData['subtotal'] = $lineItem->subtotal;
			$lineItemData['subtotalAsCurrency'] = $lineItem->subtotalAsCurrency;
			$lineItemData['tax'] = $lineItem->tax;
			$lineItemData['taxAsCurrency'] = $lineItem->taxAsCurrency;
			$lineItemData['taxCategoryId'] = $lineItem->taxCategoryId;
			$lineItemData['taxIncluded'] = $lineItem->taxIncluded;
			$lineItemData['taxIncludedAsCurrency'] = $lineItem->taxIncludedAsCurrency;
			$lineItemData['total'] = $lineItem->total;
			$lineItemData['totalAsCurrency'] = $lineItem->totalAsCurrency;
			$lineItemData['uid'] = $lineItem->uid;
			$lineItemData['weight'] = $lineItem->weight;
			$lineItemData['width'] = $lineItem->width;

			// Get the purchasable (variant) of the line item, its parent product, and the variants image
			$variant = Variant::find()->id($lineItem->purchasableId)->one();
			$product = $variant->getProduct();
			$image = $variant->image->one();

			// Add the custom field values that we need from the variant
			$lineItemData['color'] = $variant->color->value;
			$lineItemData['size'] = $variant->size->value;
			$lineItemData['image'] = [
				'alt' => $image->alt,
				'extension' => $image->extension,
				'filename' => $image->filename,
				'focalPoint' => $image->focalPoint,
				'hasFocalPoint' => $image->hasFocalPoint,
				'height' => $image->height,
				'uri' => $image->uri,
				'url' => $image->url,
				'width' => $image->width
			];

			// Add the values from the product
			$lineItemData['title'] = $product->title;
			$lineItemData['uri'] = $product->uri;
			$lineItemData['url'] = $product->url;
			$lineItemData['slug'] = $product->slug;

			// Push it to our line items array
			$lineItems[] = $lineItemData;
		}

		// Replace the current cart line items with the ones we have recreated
		$data['lineItems'] = $lineItems;

		return $data;
	}
}
