# Endpoints

## Search

Quoting heavily from [the documentation](https://nominatim.org/release-docs/latest/api/Search/):

> The search API allows you to look up a location from a textual description or address.
> The search query may also contain special phrases which are translated into specific OpenStreetMap (OSM) tags (e.g. Pub => amenity=pub). 
> This can be used to narrow down the kind of objects to be returned.

In short, if you want to use a free-form text string to search for an address or location, this is the endpoint to use. 
I won't rehash the documentation here, as it is pretty thorough.
So if you want to know the underlying details, check them out.

For this library, it currently supports some of the available parameters, but not all of them. 
To save time knowing which ones, check the table below.

| Option              | Section             | Status              |
|---------------------|---------------------|---------------------|
| `format`            | Output Format       | Implemented         |
| `json_callback`     | Output Format       | **Not implemented** |
| `addressdetails`    | Output Details      | Implemented         |
| `extratags`         | Output Details      | Implemented         |
| `namedetails`       | Output Details      | Implemented         |
| `accept-language`   | Language of Results | **Not implemented** |
| `countrycodes`      | Result Limitation   | **Not implemented** |
| `exclude_place_ids` | Result Limitation   | **Not implemented** |
| `limit`             | Result Limitation   | Implemented         |
| `viewbox`           | Result Limitation   | **Not implemented** |
| `bounded`           | Result Limitation   | **Not implemented** |
| `polygon_geojson`   | Polygon Output      | **Not implemented** |
| `polygon_kml`       | Polygon Output      | **Not implemented** |
| `polygon_svg`       | Polygon Output      | **Not implemented** |
| `polygon_text`      | Polygon Output      | **Not implemented** |
| `email`             | Other               | **Not implemented** |
| `dedupe`            | Other               | Implemented         |
| `debug`             | Other               | Implemented         |

### Usage 

To search for a location using a free-form string, you need to initialise an `OpenStreetMap` object and call it's `search()` method, as in the example below.

```php
$openStreetMap = new OpenStreetMap($client, $language);

$searchResult = $openStreetMap
    ->search(
        "135 pilkington avenue, birmingham",
        ResponseFormat::JSON,
    );
```

The example above will search using the supplied search string and request that the response's body be in JSON format.
You can request other formats, but only JSON is currently implemented.
The search result will be a hydrated array of JsonSearchResult objects, making the response, potentially, easier to work with.

If you want the raw response body, un-hydrated, then set returnRaw to true, as in the example below.

```php
$openStreetMap = new OpenStreetMap($client, $language);

$searchResult = $openStreetMap
    ->search(
        "135 pilkington avenue, birmingham",
        ResponseFormat::JSON,
        returnRaw: true,
    );
```

#### Default Search Parameters

By default, the function supplies four search parameters:

| Option          | Type           | Default Value                              | Available Values                                                                           |
|-----------------|----------------|--------------------------------------------|--------------------------------------------------------------------------------------------|
| q               | string         | The supplied search string.                | n/a                                                                                        |
| limit           | int            | 10                                         | An integer between 10 and 50.                                                              |
| format          | ResponseFormat | `'json'`. (`ResponseFormat::JSON->value`). |                                                                                            |
| accept-language | string         | `'en-au'`                                  | Any [RFC2616 accept-language string](https://www.rfc-editor.org/rfc/rfc2616#section-14.4). |

#### Specifying Additional Search Parameters

Search options, in addition to the default four need to be supplied by passing a `SearchOptions` object to the method.
You can see an example of doing so, below.

```php
$searchOptions = new SearchOptions(
    showAddressDetails: true,
    deDupeResults: true,
);

// Search for an address with the base set of options, plus two additional ones.
// The records will be de-duplicated and contain address details for the location, should it be located.
// The response body to be in JSON format.
$searchResult = $openStreetMap
    ->search(
        "135 pilkington avenue, birmingham",
        ResponseFormat::JSON,
        limit: 1,
        searchOptions: $searchOptions,
        returnRaw: true
    );
```
