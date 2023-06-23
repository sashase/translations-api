# Translation API

## Endpoints

- GET /api/articles?language={language_code}&per_page={number} getting translations for specific language
- GET /api/articles/{id} getting particular article with its translations
- POST /api/articles creation a new articles
    - Request body example-
      ```"translations": [{"title":"some title", "text": "some text", "language_code": "en"}...]``` (all 3 translations
      are required (en,ar,ja))
- PUT /api/articles/{article_id} editing particular article (same request body as in POST)
- DELETE /api/articles/ mass deletion of articles
    - Request body example - ```"articles": [1,2,3,4,5]```

## Code Style Checker

- run ```composer check-style``` to check style errors
- run ```composer fix-style``` to fix style errors
- run ```composer fix-style {dir/file name}``` to fix errors inside a particular directory or file
