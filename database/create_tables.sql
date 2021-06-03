CREATE TABLE  IF NOT EXISTS recipe(
	id BIGSERIAL PRIMARY KEY,
	title varchar(255) UNIQUE NOT NULL,
	description text
);

COMMENT ON TABLE recipe IS 'table containing information about recipes';
COMMENT ON COLUMN recipe.id IS 'unique id of recipe in table';
COMMENT ON COLUMN recipe.title IS 'title of the recipe';
COMMENT ON COLUMN recipe.description IS 'some description about recipe: tutorial how to cook';


CREATE TABLE IF NOT EXISTS ingredient(
	id BIGSERIAL PRIMARY KEY,
	title varchar(255) UNIQUE NOT NULL,
	vitamins JSON
);

COMMENT ON TABLE ingredient IS 'table containing information about different ingredients with vitamins';
COMMENT ON COLUMN ingredient.id IS 'unique id of ingredient in table';
COMMENT ON COLUMN ingredient.title IS 'name of the ingredient';
COMMENT ON COLUMN ingredient.vitamins IS 'information about vitamin content in ingredients, represented in JSON format: {"Vitamins name":"amount in milligrams"}';


CREATE TABLE  IF NOT EXISTS recipe_ingredient(
	recipe_id BIGINT REFERENCES recipe(id),
	ingredient_id BIGINT REFERENCES ingredient(id),
	ingredient_amount_in_grams DECIMAL(1000, 2),
	CONSTRAINT unique_pair UNIQUE(recipe_id, ingredient_id),
    CONSTRAINT recipe_ingredient_pkey PRIMARY KEY (recipe_id, ingredient_id)
);

CREATE INDEX IF NOT EXISTS recipe_idx ON recipe(id);
CREATE INDEX IF NOT EXISTS ingredient_id ON ingredient(id);

COMMENT ON TABLE recipe_ingredient IS 'table of many-to-many relation of recipe and ingredient tables';
COMMENT ON COLUMN recipe_ingredient.recipe_id IS 'id of recipe from recipe table';
COMMENT ON COLUMN recipe_ingredient.ingredient_id IS 'id of ingredient from ingredient table that is used in this recipe';
COMMENT ON COLUMN recipe_ingredient.ingredient_amount_in_grams IS 'amount of ingredient used for the recipe in grams';

CREATE TABLE IF NOT EXISTS template(
	id BIGSERIAL PRIMARY KEY,
	title varchar(255) UNIQUE NOT NULL,
	include_ingredients JSON,
    include_vitamins JSON,
    exclude_ingredients JSON,
    exclude_vitamins JSON
);

COMMENT ON TABLE template IS 'table containing information about templates that can be used to choose certain recipes containing certain vitamins';
COMMENT ON COLUMN template.id IS 'unique id of template in the table';
COMMENT ON COLUMN template.title IS 'title of the template';
COMMENT ON COLUMN template.include_ingredients IS 'array in json format of ingredients that must be included in query. format: ["Ингредиент1", "Ингредиент2"]';
COMMENT ON COLUMN template.include_vitamins IS 'array in json format of vitamins that must be included in query.  format: ["B2", "A"]';
COMMENT ON COLUMN template.exclude_ingredients IS 'array in json format of ingredients that must be excluded from query for recipes. same format as for include_ingredients field';
COMMENT ON COLUMN template.exclude_vitamins IS 'array in json format of vitamins that must be excluded from query for recipes. same format as for include_vitamins field';




