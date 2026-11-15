CREATE SCHEMA IF NOT EXISTS auth;
CREATE SCHEMA IF NOT EXISTS patrimoine;
CREATE SCHEMA IF NOT EXISTS localisation;

CREATE TABLE auth.users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(30) NOT NULL DEFAULT 'agent',
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE patrimoine.categories (
  id BIGSERIAL PRIMARY KEY,
  code VARCHAR(20) UNIQUE NOT NULL,
  libelle VARCHAR(120) NOT NULL
);

CREATE TABLE patrimoine.biens (
  id BIGSERIAL PRIMARY KEY,
  code_inventaire VARCHAR(40) UNIQUE NOT NULL,
  libelle VARCHAR(180) NOT NULL,
  category_id BIGINT NOT NULL REFERENCES patrimoine.categories(id),
  date_acquisition DATE NOT NULL,
  valeur_acquisition NUMERIC(15,2) NOT NULL CHECK (valeur_acquisition >= 0),
  duree_amortissement_mois INT,
  etat VARCHAR(20) NOT NULL DEFAULT 'neuf',
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE localisation.sites (
  id BIGSERIAL PRIMARY KEY,
  code VARCHAR(20) UNIQUE NOT NULL,
  nom VARCHAR(120) NOT NULL,
  ville VARCHAR(80),
  region VARCHAR(80)
);

CREATE TABLE localisation.affectations (
  id BIGSERIAL PRIMARY KEY,
  bien_id BIGINT NOT NULL,
  site_id BIGINT NOT NULL REFERENCES localisation.sites(id),
  user_id BIGINT NOT NULL,
  date_debut DATE NOT NULL,
  date_fin DATE
);