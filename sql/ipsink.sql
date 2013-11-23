--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: ipsink; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON DATABASE ipsink IS 'IP Sink v.1';


--
-- Name: networks; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA networks;


ALTER SCHEMA networks OWNER TO postgres;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = networks, pg_catalog;

--
-- Name: nextips_for(cidr); Type: FUNCTION; Schema: networks; Owner: postgres
--

CREATE FUNCTION nextips_for(cidr) RETURNS SETOF inet
    LANGUAGE sql
    AS $_$SELECT sub.ip FROM (SELECT set_masklen(((generate_series(1, (2 ^ (32 -
masklen($1::cidr)))::integer - 2) +
$1::cidr)::inet), 32) as ip) AS sub
WHERE sub.ip NOT IN
(SELECT address from networks.hosts)
AND sub.ip > set_masklen($1, 32)
AND sub.ip < set_masklen(broadcast($1)::inet, 32);$_$;


ALTER FUNCTION networks.nextips_for(cidr) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: hosts; Type: TABLE; Schema: networks; Owner: postgres; Tablespace: 
--

CREATE TABLE hosts (
    id integer NOT NULL,
    address inet,
    "desc" text,
    hostname text
);


ALTER TABLE networks.hosts OWNER TO postgres;

--
-- Name: hosts_id_seq; Type: SEQUENCE; Schema: networks; Owner: postgres
--

CREATE SEQUENCE hosts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE networks.hosts_id_seq OWNER TO postgres;

--
-- Name: hosts_id_seq; Type: SEQUENCE OWNED BY; Schema: networks; Owner: postgres
--

ALTER SEQUENCE hosts_id_seq OWNED BY hosts.id;


--
-- Name: supernets; Type: TABLE; Schema: networks; Owner: postgres; Tablespace: 
--

CREATE TABLE supernets (
    network cidr,
    description text,
    id integer NOT NULL,
    is_supernet boolean
);


ALTER TABLE networks.supernets OWNER TO postgres;

--
-- Name: supernets_id_seq; Type: SEQUENCE; Schema: networks; Owner: postgres
--

CREATE SEQUENCE supernets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE networks.supernets_id_seq OWNER TO postgres;

--
-- Name: supernets_id_seq; Type: SEQUENCE OWNED BY; Schema: networks; Owner: postgres
--

ALTER SEQUENCE supernets_id_seq OWNED BY supernets.id;


--
-- Name: id; Type: DEFAULT; Schema: networks; Owner: postgres
--

ALTER TABLE ONLY hosts ALTER COLUMN id SET DEFAULT nextval('hosts_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: networks; Owner: postgres
--

ALTER TABLE ONLY supernets ALTER COLUMN id SET DEFAULT nextval('supernets_id_seq'::regclass);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

