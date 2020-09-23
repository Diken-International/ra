--
-- PostgreSQL database dump
--

-- Dumped from database version 12.4 (Debian 12.4-1.pgdg100+1)
-- Dumped by pg_dump version 12.4 (Debian 12.4-1.pgdg100+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE ONLY public.users DROP CONSTRAINT users_branch_office_id_foreign;
ALTER TABLE ONLY public.todos DROP CONSTRAINT todos_technical_id_foreign;
ALTER TABLE ONLY public.todos DROP CONSTRAINT todos_client_id_foreign;
ALTER TABLE ONLY public.services DROP CONSTRAINT services_branch_office_id_foreign;
ALTER TABLE ONLY public.report_services DROP CONSTRAINT report_services_service_id_foreign;
ALTER TABLE ONLY public.report_services DROP CONSTRAINT report_services_product_user_id_foreign;
ALTER TABLE ONLY public.repair_parts DROP CONSTRAINT repair_parts_product_repair_parts_id_foreign;
ALTER TABLE ONLY public.repair_parts DROP CONSTRAINT repair_parts_category_repair_parts_id_foreign;
ALTER TABLE ONLY public.repair_parts DROP CONSTRAINT repair_parts_branch_office_id_foreign;
ALTER TABLE ONLY public.products DROP CONSTRAINT products_category_id_foreign;
ALTER TABLE ONLY public.products DROP CONSTRAINT products_branch_office_id_foreign;
ALTER TABLE ONLY public.product_user DROP CONSTRAINT product_user_user_id_foreign;
ALTER TABLE ONLY public.product_user DROP CONSTRAINT product_user_product_id_foreign;
ALTER TABLE ONLY public.product_service DROP CONSTRAINT product_service_service_id_foreign;
ALTER TABLE ONLY public.product_service DROP CONSTRAINT product_service_product_id_foreign;
ALTER TABLE ONLY public.messages DROP CONSTRAINT messages_services_id_foreign;
ALTER TABLE ONLY public.messages DROP CONSTRAINT messages_branch_office_id_foreign;
ALTER TABLE ONLY public.messages DROP CONSTRAINT messages_author_id_foreign;
DROP INDEX public.password_resets_email_index;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
ALTER TABLE ONLY public.todos DROP CONSTRAINT todos_pkey;
ALTER TABLE ONLY public.services DROP CONSTRAINT services_pkey;
ALTER TABLE ONLY public.report_services DROP CONSTRAINT report_services_pkey;
ALTER TABLE ONLY public.repair_parts DROP CONSTRAINT repair_parts_pkey;
ALTER TABLE ONLY public.products_repair_parts DROP CONSTRAINT products_repair_parts_pkey;
ALTER TABLE ONLY public.products DROP CONSTRAINT products_pkey;
ALTER TABLE ONLY public.product_user DROP CONSTRAINT product_user_pkey;
ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
ALTER TABLE ONLY public.messages DROP CONSTRAINT messages_pkey;
ALTER TABLE ONLY public.files DROP CONSTRAINT files_pkey;
ALTER TABLE ONLY public.category_repair_parts DROP CONSTRAINT category_repair_parts_pkey;
ALTER TABLE ONLY public.category DROP CONSTRAINT category_pkey;
ALTER TABLE ONLY public.branch_offices DROP CONSTRAINT branch_offices_pkey;
ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.todos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.services ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.report_services ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.repair_parts ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.products_repair_parts ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.products ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.product_user ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.messages ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.files ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.category_repair_parts ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.category ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.branch_offices ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE public.users_id_seq;
DROP SEQUENCE public.todos_id_seq;
DROP SEQUENCE public.services_id_seq;
DROP SEQUENCE public.report_services_id_seq;
DROP TABLE public.report_services;
DROP SEQUENCE public.repair_parts_id_seq;
DROP TABLE public.repair_parts;
DROP SEQUENCE public.products_repair_parts_id_seq;
DROP TABLE public.products_repair_parts;
DROP SEQUENCE public.products_id_seq;
DROP TABLE public.products;
DROP SEQUENCE public.product_user_id_seq;
DROP TABLE public.product_user;
DROP TABLE public.product_service;
DROP TABLE public.password_resets;
DROP SEQUENCE public.migrations_id_seq;
DROP TABLE public.migrations;
DROP SEQUENCE public.messages_id_seq;
DROP TABLE public.messages;
DROP SEQUENCE public.files_id_seq;
DROP TABLE public.files;
DROP SEQUENCE public.category_repair_parts_id_seq;
DROP TABLE public.category_repair_parts;
DROP SEQUENCE public.category_id_seq;
DROP TABLE public.category;
DROP SEQUENCE public.branch_offices_id_seq;
DROP TABLE public.branch_offices;
DROP VIEW public.all_activities;
DROP TABLE public.users;
DROP TABLE public.todos;
DROP TABLE public.services;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: services; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.services (
    id integer NOT NULL,
    client_id integer NOT NULL,
    technical_id integer NOT NULL,
    tentative_date timestamp(0) without time zone NOT NULL,
    branch_office_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    kms integer DEFAULT 0 NOT NULL,
    type character varying(255) DEFAULT 'face-to-face'::character varying NOT NULL,
    activity character varying(255) DEFAULT 'corrective'::character varying NOT NULL
);


ALTER TABLE public.services OWNER TO postgres;

--
-- Name: todos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.todos (
    id integer NOT NULL,
    description character varying(255),
    type character varying(255) NOT NULL,
    activity character varying(255) NOT NULL,
    date timestamp(0) without time zone NOT NULL,
    kms integer NOT NULL,
    technical_id integer NOT NULL,
    client_id integer,
    status character varying(255) NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.todos OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    last_name character varying(255),
    second_last_name character varying(255),
    activities json DEFAULT '[]'::json NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    role character varying(255) NOT NULL,
    branch_office_id integer,
    business_name character varying(255),
    rfc character varying(255),
    company_name character varying(255),
    contacts json DEFAULT '[]'::json NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: all_activities; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.all_activities AS
 SELECT union_data.id,
    union_data.type,
    union_data.technical_id,
    union_data.client_id,
    union_data.date_activity,
    union_data.activity,
    union_data.type_activity,
    union_data.kms,
    union_data.description,
    ( SELECT users.company_name
           FROM public.users
          WHERE (users.id = union_data.client_id)) AS client_name,
    ( SELECT users.name
           FROM public.users
          WHERE (users.id = union_data.technical_id)) AS technical_name
   FROM ( SELECT todos.id,
            'todo'::text AS type,
            todos.technical_id,
            todos.client_id,
            todos.date AS date_activity,
            todos.activity,
            todos.type AS type_activity,
            todos.kms,
            todos.description
           FROM public.todos
        UNION ALL
         SELECT services.id,
            'service'::text AS type,
            services.technical_id,
            services.client_id,
            services.tentative_date AS date_activity,
            services.activity,
            services.type AS type_activity,
            services.kms,
            'servicio de mantenimiento'::character varying AS description
           FROM public.services) union_data
  ORDER BY union_data.date_activity;


ALTER TABLE public.all_activities OWNER TO postgres;

--
-- Name: branch_offices; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.branch_offices (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.branch_offices OWNER TO postgres;

--
-- Name: branch_offices_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.branch_offices_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.branch_offices_id_seq OWNER TO postgres;

--
-- Name: branch_offices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.branch_offices_id_seq OWNED BY public.branch_offices.id;


--
-- Name: category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.category (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.category OWNER TO postgres;

--
-- Name: category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.category_id_seq OWNER TO postgres;

--
-- Name: category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.category_id_seq OWNED BY public.category.id;


--
-- Name: category_repair_parts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.category_repair_parts (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.category_repair_parts OWNER TO postgres;

--
-- Name: category_repair_parts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.category_repair_parts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.category_repair_parts_id_seq OWNER TO postgres;

--
-- Name: category_repair_parts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.category_repair_parts_id_seq OWNED BY public.category_repair_parts.id;


--
-- Name: files; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.files (
    id integer NOT NULL,
    model character varying(255) NOT NULL,
    model_id integer NOT NULL,
    path character varying(255) NOT NULL,
    category character varying(255) DEFAULT ''::character varying NOT NULL,
    type character varying(255) DEFAULT 'default'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.files OWNER TO postgres;

--
-- Name: files_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.files_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.files_id_seq OWNER TO postgres;

--
-- Name: files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.files_id_seq OWNED BY public.files.id;


--
-- Name: messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.messages (
    id integer NOT NULL,
    message text NOT NULL,
    author_id integer NOT NULL,
    branch_office_id integer NOT NULL,
    priority integer NOT NULL,
    services_id integer NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.messages OWNER TO postgres;

--
-- Name: messages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.messages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messages_id_seq OWNER TO postgres;

--
-- Name: messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.messages_id_seq OWNED BY public.messages.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- Name: product_service; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.product_service (
    product_id integer NOT NULL,
    service_id integer NOT NULL
);


ALTER TABLE public.product_service OWNER TO postgres;

--
-- Name: product_user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.product_user (
    id integer NOT NULL,
    product_id integer NOT NULL,
    user_id integer NOT NULL,
    status boolean DEFAULT true NOT NULL,
    product_type character varying(255) DEFAULT 'own'::character varying NOT NULL,
    period_service integer DEFAULT 30 NOT NULL,
    next_service date DEFAULT '2020-10-16'::date NOT NULL,
    last_service date DEFAULT '2020-09-16'::date NOT NULL,
    serial_number character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.product_user OWNER TO postgres;

--
-- Name: product_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.product_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_user_id_seq OWNER TO postgres;

--
-- Name: product_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.product_user_id_seq OWNED BY public.product_user.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products (
    id integer NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    category_id integer NOT NULL,
    specifications_desing character varying(255) NOT NULL,
    specifications_operation character varying(255) NOT NULL,
    benefits character varying(255),
    cost integer,
    price integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    branch_office_id integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.products OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.products_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_id_seq OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: products_repair_parts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products_repair_parts (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.products_repair_parts OWNER TO postgres;

--
-- Name: products_repair_parts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.products_repair_parts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_repair_parts_id_seq OWNER TO postgres;

--
-- Name: products_repair_parts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.products_repair_parts_id_seq OWNED BY public.products_repair_parts.id;


--
-- Name: repair_parts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.repair_parts (
    id integer NOT NULL,
    code character varying(255),
    number_diken integer,
    category_repair_parts_id integer NOT NULL,
    product_repair_parts_id integer NOT NULL,
    name character varying(255),
    features character varying(255),
    quantity character varying(255) NOT NULL,
    number_of_part integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    branch_office_id integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.repair_parts OWNER TO postgres;

--
-- Name: repair_parts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.repair_parts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.repair_parts_id_seq OWNER TO postgres;

--
-- Name: repair_parts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.repair_parts_id_seq OWNED BY public.repair_parts.id;


--
-- Name: report_services; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.report_services (
    id integer NOT NULL,
    service_id integer NOT NULL,
    product_user_id integer NOT NULL,
    costs json DEFAULT '[]'::json NOT NULL,
    costs_repairs json DEFAULT '[]'::json NOT NULL,
    subtotal double precision,
    total double precision,
    progress integer DEFAULT 0 NOT NULL,
    description character varying(255),
    status character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    dilution character varying(255),
    frequency character varying(255),
    method character varying(255),
    service_end date,
    service_start date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.report_services OWNER TO postgres;

--
-- Name: report_services_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.report_services_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.report_services_id_seq OWNER TO postgres;

--
-- Name: report_services_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.report_services_id_seq OWNED BY public.report_services.id;


--
-- Name: services_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.services_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.services_id_seq OWNER TO postgres;

--
-- Name: services_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.services_id_seq OWNED BY public.services.id;


--
-- Name: todos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.todos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.todos_id_seq OWNER TO postgres;

--
-- Name: todos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.todos_id_seq OWNED BY public.todos.id;


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: branch_offices id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.branch_offices ALTER COLUMN id SET DEFAULT nextval('public.branch_offices_id_seq'::regclass);


--
-- Name: category id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category ALTER COLUMN id SET DEFAULT nextval('public.category_id_seq'::regclass);


--
-- Name: category_repair_parts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_repair_parts ALTER COLUMN id SET DEFAULT nextval('public.category_repair_parts_id_seq'::regclass);


--
-- Name: files id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files ALTER COLUMN id SET DEFAULT nextval('public.files_id_seq'::regclass);


--
-- Name: messages id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messages ALTER COLUMN id SET DEFAULT nextval('public.messages_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: product_user id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_user ALTER COLUMN id SET DEFAULT nextval('public.product_user_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: products_repair_parts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products_repair_parts ALTER COLUMN id SET DEFAULT nextval('public.products_repair_parts_id_seq'::regclass);


--
-- Name: repair_parts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.repair_parts ALTER COLUMN id SET DEFAULT nextval('public.repair_parts_id_seq'::regclass);


--
-- Name: report_services id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_services ALTER COLUMN id SET DEFAULT nextval('public.report_services_id_seq'::regclass);


--
-- Name: services id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.services ALTER COLUMN id SET DEFAULT nextval('public.services_id_seq'::regclass);


--
-- Name: todos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.todos ALTER COLUMN id SET DEFAULT nextval('public.todos_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: branch_offices; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.branch_offices (id, name, created_at, updated_at) FROM stdin;
1	Sucursal Celaya	2020-09-16 09:17:28	2020-09-16 09:17:28
\.


--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.category (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	Limpieza y sanitación de áreas y equipos de proceso	Limpieza y sanitación de áreas y equipos de proceso	\N	\N	\N
\.


--
-- Data for Name: category_repair_parts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.category_repair_parts (id, name) FROM stdin;
1	Equipos espumados móviles
2	Equipo Espumador Presurizado
3	Equipos Espumadores de Pared
4	Equipos Sanitizadores
5	Equipos Nebulizador
6	Central System
7	Equipo de Drenajes
8	Trasvase Neumático
9	CSS
\.


--
-- Data for Name: files; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.files (id, model, model_id, path, category, type, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: messages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.messages (id, message, author_id, branch_office_id, priority, services_id, deleted_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2020_05_10_040311_add_role_field_in_user_table	1
4	2020_05_12_034333_add_table_branch_offices	1
5	2020_05_12_034656_add_branch_office_in_users_table	1
6	2020_05_17_185825_create_service_table	1
7	2020_05_24_034833_create_files_table	1
8	2020_06_01_163840_create_messages_table	1
9	2020_06_14_032709_create_category_table	1
10	2020_06_14_033712_create_products_table	1
11	2020_06_14_033838_create_category_repair_parts	1
12	2020_06_14_033839_create_products_repair_parts	1
13	2020_06_14_034258_create_repair_parts_table	1
14	2020_07_13_142208_add_foreign_keys_in_products_table	1
15	2020_07_19_032345_add_extra_fields_in_tables	1
16	2020_07_29_002842_create_report_services_table	1
17	2020_08_18_181913_create_todos_table	1
18	2020_08_18_193538_add_field_type_in_services_table	1
19	2020_08_20_213138_add_fields_company_in_users_table	1
20	2020_08_21_221053_create_todos_view	1
\.


--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: product_service; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.product_service (product_id, service_id) FROM stdin;
\.


--
-- Data for Name: product_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.product_user (id, product_id, user_id, status, product_type, period_service, next_service, last_service, serial_number, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products (id, code, name, description, category_id, specifications_desing, specifications_operation, benefits, cost, price, created_at, updated_at, deleted_at, branch_office_id) FROM stdin;
1	CCS-213-2	CCS-D Cart 5 Galones	Espumador Portátil, Opera con Suministro de Aire.        El equipo D’Cart 5 es un sistema de generación de espuma operado por bomba neumática la cuál genera al 100% la capacidad de espuma detergente.\nEquipo compacto para uso en áreas de espacios restring	1	• Tanque móvil de plástico con acabado sanitario y de alta resistencia.\n• Capacidad: 5 galones.\n• Medidas de tanque: 50 x 65 x 20 cm.\n• Manguera flexible de ½” de 5 m de largo operada con válvula de esfera de inoxidable.\n• Regulador de presión con man	Presión de aire requerida: 60 – 80 psi de aire limpio y filtrado.\nConsumo de aire: 3 a 4 cfm.\nConsumo de solución detergente: 4 L/min.\nÁrea de cobertura del contenido total del tanque: 48 m2.\n	Costo muy accesible del equipo.\nGeneración de espuma al 100% = reducción de gastos de detergente.\nDisminución de tiempos muertos en limpieza y aumento de tiempo neto de operación.\nCobertura total de aplicación.\nMayor tiempo de contacto de detergentes 	\N	\N	\N	\N	\N	1
2	CCS-167-9\n	CCS-D’Cart 10 Galones 	Espumador Portátil, Opera con Suministro de Aire.      El equipo D’Cart 10 es un sistema de generación de espuma operado por bomba neumática la cuál genera al 100% la capacidad de espuma detergente.\n	1	\nTanque móvil de plástico con acabado sanitario y de alta resistencia.\n• Capacidad: 10 galones.\n• Manguera flexible de ½” de 10 m de largo operada con válvula de esfera de inoxidable.\n• Regulador de presión con manómetro indicador de 0 – 100 psi.\n• Vá	\nPresión de aire requerida: 60 – 80 psi de aire limpio y filtrado.\n• Consumo de aire: 3 a 4 cfm.\n• Consumo de solución detergente: 4 L/min.\n• Área de cobertura del contenido total del tanque: 96 m2.\n	\n• Costo muy accesible del equipo.\n• Generación de espuma al 100% = reducción de gastos de detergente.\n• Disminución de tiempos muertos en limpieza y aumento de tiempo neto de operación.\n• Cobertura total de aplicación.\n• Mayor tiempo de contacto de d	\N	\N	\N	\N	\N	1
3	CCS-203-1\n	CCS-D’Cart 20 Galones 	Espumador Portátil, Opera con Suministro de Aire.        El equipo D’Cart 20 es un sistema de generación de espuma operado por bomba neumática la cuál genera al 100% la capacidad de espuma detergente.\n	1	Tanque móvil de plástico con acabado sanitario y de alta resistencia.\nCapacidad: 20 galones.\nManguera flexible de ½” de 10 m de largo operada con válvula de esfera de inoxidable.\nRegulador de presión con manómetro indicador de 0 – 100 psi.\nVálvula reg	Presión de aire requerida: 60 – 80 psi de aire limpio y filtrado.\nConsumo de aire: 3 a 4 cfm.\nConsumo de solución detergente: 4 L/min.\nÁrea de cobertura del contenido total del tanque: 200 m2.\n	Costo muy accesible del equipo.\nGeneración de espuma al 100% = reducción de gastos de detergente.\nDisminución de tiempos muertos en limpieza y aumento de tiempo neto de operación.\nCobertura total de aplicación.\nMayor tiempo de contacto de detergentes 	\N	\N	\N	\N	\N	1
4	CCS-168-2\n	CCS-D’Cart 30 Galones 	Espumador Portátil, Opera con Suministro de Aire.            El equipo D’Cart 30 es un sistema de generación de espuma operado por bomba neumática la cuál genera al 100% la capacidad de espuma detergente.\n	1	• Tanque móvil de plástico con acabado sanitario y de alta resistencia.\n• Capacidad: 30 galones.\n• Manguera flexible de ½” de 10 m de largo operada con válvula de esfera de inoxidable.\n• Regulador de presión con manómetro indicador de 0 – 100 psi.\n• V	Presión de aire requerida: 60 – 80 psi de aire limpio y filtrado.\nConsumo de aire: 3 a 4 cfm.\nConsumo de solución detergente: 4 L/min.\nÁrea de cobertura del contenido total del tanque: 300 m2.\n	Costo muy accesible del equipo.\nGeneración de espuma al 100% = reducción de gastos de detergente.\nDisminución de tiempos muertos en limpieza y aumento de tiempo neto de operación.\nCobertura total de aplicación.\nMayor tiempo de contacto de detergentes 	\N	\N	\N	\N	\N	1
5	CCS-204-4\n	CCS-D'Cart 20 Galones Duplex	Espumador y Sanitizador Portátil Opera con Suministro de Aire.  El equipo D’Cart Duplex es un espumador y sanitizador portátil operado por medio de una bomba para una óptima calidad de espuma y sanitizante.\n	1	Tanque móvil de plástico de alta resistencia de capacidad hasta 20 galones.\nTanque para sanitizante de 10 lts.\nManguera flexible de ½” por 10 metros de largo con válvula de salida.\nVálvula de 3 vías 2 posiciones para selección de sanitizante o detergen	Presión de aire requerida: 60 a 80 psi.\nConsumo de aire: 3 a 4 cfm.\nConsumo: agua / químico 4 litros por minuto, cubriendo un área de 10 m2. 	Inocuidad 100%.\nReducción de mano de obra directa.\nIncremento de tiempo neto de operación.\nAhorro en consumo de agua.\nAhorro en consumo de equipo de limpieza y químicos. 	\N	\N	\N	\N	\N	1
6	CCS-205-7\n	Steel Foam 16 Galones 	Espumador Portátil Presurizado.\nEquipo generador de espuma basado en la compresión de solución detergente en un tanque presurizado.\n	1	Tanque integrado en acero inoxidable para transportarlo fácilmente.\nResistencia de tanque certificada.\nVálvula de seguridad calibrada a 100 libras de presión.\nManómetro para verificar presión interna del equipo.\nVálvula reguladora de flujo de aire par	Presión de aire requerida: 60 a 80 psi de aire limpio y filtrado.\nConsumo de aire: 5 a 6 cfm.\nConsumo de solución detergente: 2 galones / minuto (el consumo varia de acuerdo a la abertura de la válvula).\nÁrea de cobertura del contenido total del tanque	Costo muy accesible del equipo.\nGeneración de espuma al 100% = reducción de gastos de detergente.\nDisminución de tiempos muertos en limpieza y aumento de tiempo neto de operación.\nCobertura total de aplicación.\nMayor tiempo de contacto de detergentes 	\N	\N	\N	\N	\N	1
7	CCS-215-8\n	Nebulizador Portátil (6 Fogger) 	Sanitizador Portátil por Nebulización.\nEl equipo 6 way fogger en un nebulizador portátil diseñado para sanitizar por medio de neblina.\n	1	Tanque en estructura de plástico resistente, móvil.\nCapacidad de tanque de 10 galones.\nBomba de doble diafragma para nebulizado óptimo.\nBoquillas diseñadas para partícula fina.\nManifull de nylamid diseñado para alta presión.\nAltura de equipo: 2.10 mt	Presión requerida de aire: 60 a 80 psi.\nConsumo de aire: 3 a 4 cfm.\nDiámetro de nebulizado: 360º.\nAlcance de nebulización: 6 x 6 m. 	Inocuidad 100%.\nReducción de mano de obra directa.\nIncremento de tiempo neto de operación.\nAhorro en consumo de agua y energía.\nAhorro en consumo de equipo de limpieza y químicos. 	\N	\N	\N	\N	\N	1
8	CCS-401-1\n	Clorinador por Pastillas por Flujómetro 	El equipo opera por medio de un flujómetro el cual manda la señal al tablero de control, que se encarga de recibir los pulsos e inyectar el hipoclorito de calcio a través del vaso clorinador.\n	1	Gabinete en acero inoxidable 304 con acabado sanitario.\nBomba para recirculación con carcaza en acero inoxidable.\nVálvula solenoide ¾” que se alimenta de 127 VAC.\nVaso clorinador específicamente para pastillas de hipoclorito.\nModulo lógico de 24 VDC. 	Línea eléctrica con alimentación de 127 VAC.\nUna sola alimentación de agua hacia la cisterna o deposito. 	Mejor control de dosificación de cloro.\nReducción de mano de obra directa.\nIncremento de tiempo neto de operación.\n	\N	\N	\N	\N	\N	1
9	CCS-402-4\n	Clorinador por Pastillas por Sonda 	El equipo opera por medio de lecturas que realiza la sonda, el cual envía la información al tablero de control, que es la encargada de comparar la lectura tomada por la sonda contra la que es registrada en el sistema.\n	1	Gabinete en acero inoxidable 304 con acabado sanitario.\nVaso clorinador específicamente para pastillas de hipoclorito.\nMódulo de control de 127 VAC.\nSonda.\nVálvula solenoide ¾” que se alimenta de 127 VAC.\n	Línea eléctrica con alimentación de 127 VAC. 	Mejor control de dosificación de cloro.\nReducción de mano de obra directa.\nIncremento de tiempo neto de operación.\nRegistro de lecturas tomadas.\n	\N	\N	\N	\N	\N	1
10	CCS-159.2-2\n\n	Central System con Bombeo Neumático 	Sistema Central de Espuma y Sanitizado.\nEs un sistema centralizado semi automático para la disolución de espuma para limpieza y sanitizado de áreas de industrias alimenticias con la finalidad de facilitar los métodos, consumos y tiempos de aplicación.\n•	1	Bomba neumática con capacidades para 5 satélites hasta 40 satélites.\nDosificadores de producto químico.\nSatélites con alcance de 15 metros para detergente y sanitizante.\nTanques de polipropileno para almacenamiento de producto químico diluido hasta 100	Presión de aire requerida: de 80 a 95 psi.\nConsumo de aire: 90 cfm para cada bomba.\nConsumo de aire: 1.5 cfm para cada bomba.        	Inocuidad 100%.\nReducción de mano de obra directa.\nIncremento de tiempo neto de operación.\nAhorro en consumo de agua (dependiendo de la abertura de la válvula)\nAhorro en consumo de equipo de limpieza y químicos. 	\N	\N	\N	\N	\N	1
11	CCS-159.1-9\n	Central System con Bombeo Neumático	Sistema Central de Espuma y Sanitizado.\nEs un sistema centralizado semi automático para la disolución de espuma para limpieza y sanitizado de áreas de industrias alimenticias con la finalidad de facilitar los métodos, consumos y tiempos de aplicación.\n•	1	Bomba neumática con capacidades para 5 satélites hasta 40 satélites.\nDosificadores de producto químico.\nSatélites con alcance de 15 metros para detergente y sanitizante.\nTanques de polipropileno para almacenamiento de producto químico diluido hasta 100	Presión de aire requerida: de 80 a 95 psi.\nConsumo de aire: 90 cfm para cada bomba.\nConsumo de aire: 1.5 cfm para cada bomba.\n	Inocuidad 100%.\nReducción de mano de obra directa.\nIncremento de tiempo neto de operación.\nAhorro en consumo de agua (dependiendo de la abertura de la válvula)\nAhorro en consumo de equipo de limpieza y químicos. 	\N	\N	\N	\N	\N	1
12	CCS-403-7\n	Intervenciones Antimicrobiales 	Equipo de Lavado y Sanitizado de Bandas de Proceso de Alimentos\nEquipo sanitizador de bandas de proceso de alimentos a través de aire a presión integrado con un venturi para un sanitizado óptimo.\n	1	Equipo integrado con válvulas para el control de flujo.\nRegulador de presión para un ajuste óptimo.\nBoquillas diseño en abanico para un baño uniforme.\n	Presión de aire: 80 a 100 psi.\nCaudal requerido: 3 a 4 cfm. 	Ahorro de químico y agua por medio de sus boquillas diseñadas para un baño uniforme y efectivo. 	\N	\N	\N	\N	\N	1
13	CCS-404-0\n	Sistema Automático de Sanitizado Múltiple 	(Espreado - Bandas)\nEl equipo CCS sanitizador de alimentos de contacto directo múltiple.\n	1	Caja de PVC y circuito hidráulico cerrado.\nVálvula reguladora.\nCampana.\nBoquillas seleccionadas según necesidades del cliente. 	Presión aire: 80 a 90 psi.\nConsumo de agua o químico por boquilla: 20 ml a 200 ml / min (según boquilla). 	Inocuidad 100%.\nAhorro de químico a través de sus boquillas seleccionadas.\n	\N	\N	\N	\N	\N	1
14	CCS-408-2\n	Sistema de Dosificación Automático para Sanitizado\n(en Línea o Abierto) 	Es un sistema automatizado para la dosificación en línea o abierto de químico concentrado tal como ácido peracético, citrosan entre otros, ideal para sanitizar áreas de industrias alimenticias con la finalidad de facilitar los métodos, consumos y tiempos 	1	Dosificador de pulsos controlado por un PLC.\nFlujómetro para medir el paso del agua.\nBombas para aplicación de producto concentrado. 	Dosificación al 100%.\nReducción de mano de obra directa para dosificación.\nReducción de tiempo en arranque. 	Presión de agua requerida de 40 a 60 psi.\nPresión de aire requerido entre 80 a 100 PSI en punto de uso.\nAlimentación de energía eléctrica de 127 VAC a 60 Hz. 	\N	\N	\N	\N	\N	1
15	CCS-409-5\n	Pedestal de Inoxidable para Basura 	Estructura para bolsa de basura en acero inoxidable.\n	1	Acero inoxidable T304.\nAcabado sanitario.\nAccionamiento por pedal.\n	s/n	s/n	\N	\N	\N	\N	\N	1
16	CCS-405-3\n	Lavabotas Automático By Diken 	Es un sistema de lavado de botas automático, de aduanas sanitarias, para lavado y enjuague en la misma estación.\n	1	Acero Inoxidable T304.\nAcabado sanitario.\nDosificación de producto concentrado liquido\nManguera de alta resistencia con pistola de alta resistencia para enjuague.\nMotor de alta resistencia.\nCepillo de color de alta resistencia para tallado de la bota	Presión de agua requerida de 40 a 60 psi.\nAlimentación de energía eléctrica 127 VAC. 	Flujo de personal en aduanas sanitarias. 	\N	\N	\N	\N	\N	1
17	CCS-406-6\n\n	Lavabotas Individual y Múltiple By Diken 	Es un sistema de lavado de botas para aduanas sanitarias, lavado y enjuague en la misma estación.\n	1	Acero inoxidable T304.\nAcabado sanitario.\nDosificación de químico concentrado líquido por medio de Venturi.\nManguera y pistola de alta resistencia para enjuague.\nDe 1 a 3 estaciones de servicio.\n	Flujo de personal en aduanas sanitarias\nPrecio competitivo. 	Presión de agua requerida de 40 a 60 psi. 	\N	\N	\N	\N	\N	1
18	CCS-407-9\n	Lavabotas Individual y Múltiple By Diken 	Es un sistema de lavado de botas para aduanas sanitarias, lavado y enjuague en la misma estación.\n	1	Acero inoxidable T304.\nAcabado sanitario.\nDosificación de químico concentrado líquido por medio de Venturi.\nManguera y pistola de alta resistencia para enjuague.\nDe 1 a 3 estaciones de servicio.\n	Flujo de personal en aduanas sanitarias\nPrecio competitivo. 	Presión de agua requerida de 40 a 60 psi. 	\N	\N	\N	\N	\N	1
\.


--
-- Data for Name: products_repair_parts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products_repair_parts (id, name) FROM stdin;
1	Despiece Serie D’Cart
2	Despiece Serie D’Cart Duplex
3	Despiece Steel Foam
4	Despiece Wall Foam
5	Despiece Mini Wall Foam
6	Door Way Sanitizer
7	Mini Wall Sanitizer
8	Nebulizador Six Foger
9	Central System
10	Lyster Quat 7” (redonda)
11	Lyster Quat 9” (redonda)
12	Lyster Quat (25 cm cuadrados)
13	Chemical Transfer
14	Cleaning Control System
\.


--
-- Data for Name: repair_parts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.repair_parts (id, code, number_diken, category_repair_parts_id, product_repair_parts_id, name, features, quantity, number_of_part, created_at, updated_at, branch_office_id) FROM stdin;
1	CCS-REF-104-8\n	1	2	3	MANÓMETRO 	Rango: 0-100 lbs.\nDimensión: De ¼” npt inferior.\nDimensión carátula: 2”.\n	1pz	\N	\N	\N	1
2	CCS-REF-530-2\n	0	2	3	NIPLE 	Material: Acero inoxidable T304.\nDiámetro: ¼”.\nDimensión: 6”.\n	1pz	\N	\N	\N	1
3	CCS-REF-360-18\n	0	2	3	CONECTOR RÁPIDO 	Material: Acero inoxidable.\nDimensiones: De ¼” macho.\n	1pz	\N	\N	\N	1
4	CCS-REF-167-6\n	4	2	3	TUBING 	Material: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	0.6	\N	\N	\N	1
5	CCS-REF-168-9\n	5	2	3	TUBING 	Material: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	1pz	\N	\N	\N	1
6	CCS-REF-169-2\n	6	2	3	CONECTOR RECTO 	Material: Latón niquelado.\nDimensiones: De ½” od a ¼” npt.\n	2	\N	\N	\N	1
7	CCS-REF-187-9\n	7	2	3	REDUCCIÓN BUSHING 	Material: Acero inoxidable.\nDimensión: De ½” npt a ¼” npt.\n	1pz	\N	\N	\N	1
8	CCS-REF-189-5\n	8	2	3	NIPLE 	Material: Acero inoxidable.\nDimensión: ¼” npt x 1”.\nRosca: Corrida.\n	4	\N	\N	\N	1
9	CCS-REF-761-6\n	9	2	3	VÁLVULA BOLA 	Material: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo.\n	1pz	\N	\N	\N	1
10	CCS-REF-419-3\n	10	2	3	TEE RAMIFICADA 	Material: Latón niquelado con cuerpo negro de nylon.\nDimensión: De 3/8” od a ¼” npt.\nCon macho giratorio.\n	1pz	\N	\N	\N	1
11	CCS-REF-427-9\n	21	2	3	MEDIO NIPLE 	Material: Acero inoxidable.\nDimensiones: De ¼” x 5”.\n	1pz	\N	\N	\N	1
12	CCS-REF-253-5\n	11	2	3	TEE 	Material: Acero inoxidable.\nDimensión: ¼”.\n	1pz	\N	\N	\N	1
13	CCS-REF-254-8\n	12	2	3	VALVULA DE SEGURIDAD 	Material: Bronce.\n100 lbs.\n	1pz	\N	\N	\N	1
14	CCS-REF-255-1\n	0	2	3	VALVULA AGUJA 	Material: Acero inoxidable.\nDimensión: ¼”.\nRosca hembra.\n	1pz	\N	\N	\N	1
15	CCS-REF-257-7\n	14	2	3	CODO 	Material: Acero inoxidable.\nDimensión: ¼” npt.\n	2	\N	\N	\N	1
16	CCS-REF-270-9\n	0	2	3	VÁLVULA CHECK 	Dimensión: 3/8” od.\nPresión: 175 psi.\n	1pz	\N	\N	\N	1
17	CCS-REF-258-0\n	15	2	3	MINIREGULADOR SIMPLE 	Material: Aluminio.\nDimensiones: Entrada y salida ¼” npt.\n	1pz	\N	\N	\N	1
18	CCS-REF-281-4\n	17	2	3	CONECTOR RECTO 	Material: Niquelado.\nDimensiones: De ¼” npt a 3/8” od.\n	1pz	\N	\N	\N	1
19	CCS-REF-414-8\n	0	2	3	ETIQUETA STEEL FOAM 	Material: Vinil.\n	1pz	\N	\N	\N	1
20	CCS-REF-319-154\n	0	2	3	MANGUERA KURIYAMA KIT INOXIDABLE 	Diámetro: ½”.\nDimensión: 10 metros.\nColor: Azul.\nModelo: k1136.\n	1pz	\N	\N	\N	1
21	CCS-REF-413-5\n	20	2	3	REDUCCIÓN CAMPANA 	Material: Acero inoxidable.\nDimensión: De ½” npt a ¼” npt.\n	1pz	\N	\N	\N	1
22	CCS-REF-484-9\n	0	2	3	AYUDA VISUAL 	Material: Vinil en PVC.\nDimensión: 24.7 x 20.5 cm.\n	1pz	\N	\N	\N	1
23	CCS-REF-193-8\n	23	2	3	VALVULA BOLA 	Material: Niquelada.\nDimensiones: De ¼” a ¼”.\n	1pz	\N	\N	\N	1
24	CCS-REF-742-6\n	27	2	3	RESORTE DE PROTECCIÓN PARA MANGUERA 	\nMaterial: Acero inoxidable 302.\nLargo de resorte : 15 cm.\nDiámetro del material: .098” ( +- .005”).\n6 vueltas en cono para presión.\n	1pz	\N	\N	\N	1
25	CCS-REF-106-4\n	2	2	3	Niple\n	De 1/4" X 3" Inox 304 Ced 40 	1pz	\N	\N	\N	1
26	CCS-REF-107-7\n	13	2	3	Valvula De Aguja\n	1/4" Rosca Hembra Bron	1pz	\N	\N	\N	1
27	CCS-REF-270-0\n	16	2	3	Valvula Check\n	 3/8 Od 175Psi 	1pz	\N	\N	\N	1
28	CCS-REF-372-8\n	18	2	3	Manguera Kuriyama\n	1/2 Azul K1136 	10pz	\N	\N	\N	1
29	CCS-REF-375-7\n	19	2	3	Espiga para Manguera	De 1/2 Npt Ai Dixon1020808ss\n	2pz	\N	\N	\N	1
30	CCS-REF-434-1\n	24	2	3	Codo 90° Macho\n	Gir 1/4 Npt 3/8" Od Nylon 	1pz	\N	\N	\N	1
31	CCS-REF-523-0\n	25	2	3	Tornillo	1/4-20X5/8 A Inox Cab/Llave Tor\n	4pz	\N	\N	\N	1
32	CCS-REF-531-5\n	26	2	3	Niple\n	1/4" X6" Acero Inoxidable	1pz	\N	\N	\N	1
33	CCS-REF-106-4\n	6	3	4	NIPLE 	Material: Acero inoxidable T304.\nDiámetro: ¼”.\nDimensión: 3”.\n	1pz	\N	\N	\N	1
34	CCS-REF-187-9\n	11	3	4	REDUCCIÓN BUSHING 	Material: Acero inoxidable.\nDimensión: De ½” npt a ¼” npt.\n	1pz	\N	\N	\N	1
35	CCS-REF-366-9\n	21	3	4	ARANDELA 	Material: Acero inoxidable.\nDiámetro interior: 7/8”.\nDiámetro exterior: 1 ½”.\nEspesor: 1/8”.\n	1pz	\N	\N	\N	1
36	CCS-REF-362-7\n	19	3	4	CONECTOR RÁPIDO 	Material: Acero inoxidable.\nDimensión: Macho ¼” a ¼” npt hembra.\n	1pz	\N	\N	\N	1
37	CCS-REF-044-1\n	3	3	4	BOMBA FLOJET 	Material: Polipropileno.\nDoble diafragma.\n	1pz	\N	\N	\N	1
38	CCS-REF-162-1\n	7	3	4	REGULADOR 	Rango: 0-150 bs e y s1 / ae man 1/8” b63-02a.\nFiltro ¼” con purga automática.\n	1pz	\N	\N	\N	1
39	CCS-REF-166-3\n	8	3	4	TEE 	Material: Acero inoxidable T304.\nDimensión: De ½” npt.\n	1pz	\N	\N	\N	1
40	CCS-REF-167-6\n	9	3	4	TUBING 	Material: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	0.40m	\N	\N	\N	1
41	CCS-REF-168-9\n	10	3	4	TUBING 	Material: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	0.80m	\N	\N	\N	1
42	CCS-REF-761-6\n	12	3	4	VÁLVULA BOLA 	Material: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo.\n	1pz	\N	\N	\N	1
43	CCS-REF-019-1\n	2	3	4	FILTRO 	Material: Tela acero inoxidable.\nDimensión: ¼” hembra npt.\n	1pz	\N	\N	\N	1
44	CCS-REF-270-9\n	14	3	4	VÁLVULA CHECK 	Dimensión: 3/8” od.\nPresión: 175 psi.\n	1pz	\N	\N	\N	1
45	CCS-REF-271-2\n	15	3	4	VÁLVULA DE CONTROL DE FLUJO 	Dimensión: 3/8” od.\n	1pz	\N	\N	\N	1
46	CCS-REF-272-5\n	16	3	4	CONECTOR RECTO 	Material: Niquelado.\nDimensiones: De ½” npt a 3/8” od.\n	1pz	\N	\N	\N	1
47	CCS-REF-277-0\n	17	3	4	PIJA 	Material: Acero inoxidable serie 300.\nDimensiones: De 1/8” x 1 ¼”.\n	2pz	\N	\N	\N	1
48	CCS-REF-319-154\n	0	3	4	MANGUERA KURIYAMA KIT INOXIDABLE 	Diámetro: ½”.\nDimensión: 10 metros.\nColor: Azul.\nModelo: k1136.\n	1pz	\N	\N	\N	1
49	CCS-REF-427-9\n	13	3	4	MEDIO NIPLE 	Material: Acero inoxidable.\nDimensiones: De ¼” x 5”.\n	1pz	\N	\N	\N	1
50	CCS-REF-092-2\n	5	3	4	MANGUERA TRAMADA 	Material: PVC flexible.\nDimensión: 3/8”.\n	1.5m	\N	\N	\N	1
51	CCS-REF-361-4\n	18	3	4	CONECTOR RECTO 	Material: Acero inoxidable.\nDimensiones: De ½” od a ½” npt.\n	1pz	\N	\N	\N	1
52	CCS-REF-479-2\n	23	1	1	Casquillo para manguera\n	1/2" acero inoxidable, largo 1 1/8" 	1pz	\N	\N	\N	1
53	CCS-REF-411-9	28	1	1	TAPA PARA TANQUE D’CART. 	a	1pz	\N	\N	\N	1
54	CCS-REF-187-9\n	6	1	1	REDUCCIÓN BUSHING 	Material: acero inoxidable.\nDe ½” npt a ¼” npt. 	1pz	\N	\N	\N	1
55	CCS-REF-281-4	26	1	1	CONECTOR RECTO 	Material: Niquelado.\nDimensiones: De ¼” npt a 3/8” od. 	1pz	\N	\N	\N	1
56	CCS-REF-199-6	29	1	1	TAPÓN DE DRENAJE DPA 	Para tanque de 20 galones.\n	1pz	\N	\N	\N	1
57	CCS-REF-044-1	1	1	1	BOMBA FLOJET 	Material: Polipropileno.\nDoble diafragma. 	1pz	\N	\N	\N	1
58	CCS-REF-162-1	2	1	1	REGULADOR 	Rango: 0-150 bs e y s1 / ae man 1/8” b63-02a.\nFiltro ¼” con purga automática.\n	1pz	\N	\N	\N	1
59	CCS-REF-166-3\n	3	1	1	TEE 	Material: Acero inoxidable T304.\nDimensión: De ½” npt. 	1pz	\N	\N	\N	1
60	CCS-REF-167-6\n	4	1	1	TUBING 	Material: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	1.10m	\N	\N	\N	1
61	CCS-REF-168-9\n	5	1	1	TUBING 	Material: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	0.80m	\N	\N	\N	1
62	CCS-REF-761-6\n	0	1	1	VÁLVULA BOLA 	Material: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo.\n	1pz	\N	\N	\N	1
63	CCS-REF-427-9\n	8	1	1	MEDIO NIPLE 	Material: Acero inoxidable t304.\nDimensiones: ¼” x 5”.\nCédula: Estándar.\n	1pz	\N	\N	\N	1
64	CCS-REF-270-9\n	9	1	1	VÁLVULA CHECK 	Dimensión: 3/8” od.\nPresión: 175 psi.\n	1pz	\N	\N	\N	1
65	CCS-REF-271-2\n	10	1	1	VÁLVULA DE CONTROL DE FLUJO 	Dimensiones: 3/8” od.\n	1pz	\N	\N	\N	1
66	CCS-REF-272-5\n	11	1	1	CONECTOR RECTO 	Material: Niquelado.\nDimensiones: De ½” npt a 3/8” od. 	1pz	\N	\N	\N	1
67	CCS-REF-277-0\n	12	1	1	PIJA 	Material: Acero inoxidable serie 300.\nDimensiones: De 1/8” x 1 ¼” . 	2pz	\N	\N	\N	1
68	CCS-REF-476-3\n	22	1	1	AYUDA VISUAL 	Material: Vinil en PVC.\nDimensión: 27.5 x 21.5 cm. 	1pz	\N	\N	\N	1
69	CCS-REF-474-7\n	21	1	1	FILTRO PARA BOMBA 	Material: Plástico.\nDimensión: Conectores de 3/8”. 	1pz	\N	\N	\N	1
70	CCS-REF-360-18\n	13	1	1	CONECTOR RÁPIDO 	Material: Acero inoxidable.\nDimensión: ¼” macho npt. 	1pz	\N	\N	\N	1
71	CCS-REF-361-4\n	14	1	1	CONECTOR RECTO 	Material: Acero inoxidable.\nDimensiones: De ½” od a ½” npt. 	1pz	\N	\N	\N	1
72	CCS-REF-365-6\n	15	1	1	ARANDELA 	Material: Acero inoxidable.\nDiámetro interior: 9/16”.\nDiámetro exterior: 1”\nEspesor: 1/16”.\n	1pz	\N	\N	\N	1
73	CCS-REF-366-9\n	16	1	1	ARANDELA 	Material: Acero inoxidable.\nDiámetro interior: 7/8”.\nDiámetro exterior: 1 ½”.\nEspesor: 1/8”. 	1pz	\N	\N	\N	1
74	CCS-REF-319-154\n	0	1	1	MANGUERA KURIYAMA KIT INOXIDABLE 	Diámetro: ½”.\nDimensión: 10 metros.\nColor: Azul.\nModelo: k1136.\n	1pz	\N	\N	\N	1
75	CCS-REF-415-1\n	20	1	1	PIJA 	Material: Acero inoxidable 304.\nDimensión: De 1/8” x 7/8”. 	2pz	\N	\N	\N	1
76	CCS-REF-486-5\n	27	1	1	ETIQUETA DE SEGURIDAD 	Material: Holograma metalizado.\n	2pz	\N	\N	\N	1
77	CCS-REF-472-1\n	24	1	1	TORNILLO 	Material: Acero inoxidable 304.\nDimensión: De ¼” - 20 x ¾” de largo.\nCabeza: Plana.\nPara llave Torx.\n	2pz	\N	\N	\N	1
78	CCS-REF-163-4\n	25	1	1	CODO 	Material: Nylon.\nDimensión: 3/8” od x 1/8” npt.\nColor: Negro.\n	1pz	\N	\N	\N	1
79	CCS-REF-385-9\n	0	1	1	ETIQUETA D’CART 5 	Material: Vinil.\n	1pz	\N	\N	\N	1
80	CCS-REF-382-0\n	0	1	1	ETIQUETA D’CART 10 	Material: Vinil.\n	1pz	\N	\N	\N	1
81	CCS-REF-383-3\n	19	1	1	ETIQUETA D’CART 20 	Material: Vinil.\n	1pz	\N	\N	\N	1
82	CCS-REF-384-6\n	0	1	1	ETIQUETA D’CART 30 	Material: Vinil.\n	1pz	\N	\N	\N	1
83	CCS-REF-743-9\n	30	1	1	RESORTE DE PROTECCIÓN PARA MANGUERA 	Material: Acero inoxidable 302.\nLargo de resorte : 15 cm.\nDiámetro del material: .098” ( +- .005”).\nCon pata de arrastre.\n	1pz	\N	\N	\N	1
84	CCS-REF-019-1\n	1	1	2	FILTRO 	Material: Tela acero inoxidable.\nDimensión: ¼” hembra npt. 	1pz	\N	\N	\N	1
85	CCS-REF-044-1\n	2	1	2	BOMBA FLOJET 	Material: Polipropileno.\nDoble diafragma. 	1pz	\N	\N	\N	1
86	CCS-REF-176-4\n	10	1	2	CONECTOR CODO 	Material: Latón niquelado.\nDimensión: ½” od a ½” npt. 	2pz	\N	\N	\N	1
87	CCS-REF-151-6\n	4	1	2	VALVULA 2 POSICIONES 	Material: Bronce.\nDimensión: ½”.\n3 vías. 	1pz	\N	\N	\N	1
88	CCS-REF-162-1\n	5	1	2	REGULADOR 	Rango: 0-150 bs e y s1 / ae man 1/8” b63-02a.\nFiltro ¼” con purga automática. 	1pz	\N	\N	\N	1
89	CCS-REF-166-3\n	7	1	2	TEE 	Material: Acero inoxidable T304.\nDimensión: De ½” npt. 	1pz	\N	\N	\N	1
90	CCS-REF-168-9\n	9	1	2	TUBING 	Material: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	0.80 m	\N	\N	\N	1
91	CCS-REF-167-6\n	8	1	2	TUBING 	Material: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	1.70 m	\N	\N	\N	1
92	CCS-REF-472-1\n	28	1	2	TORNILLO 	Material: Acero inoxidable 304.\nDimensión: De ¼” - 20 x ¾” de largo.\nCabeza: Plana.\nPara llave Torx.\n	1pz	\N	\N	\N	1
93	CCS-REF-189-5\n	12	1	2	NIPLE 	Material: Acero inoxidable.\nDimensión: ¼” npt x 1”.\nRosca: Corrida.\n	2pz	\N	\N	\N	1
94	CCS-REF-761-6\n	0	1	2	VÁLVULA BOLA 	Material: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo. 	1pz	\N	\N	\N	1
95	SN1	0	1	2	VÁLVULA CHECK 	Dimensión: 3/8” od.\nPresión: 175 psi.\n	1pz	\N	\N	\N	1
96	SN2	0	1	2	VÁLVULA DE CONTROL DE FLUJO 	Dimensión: 3/8” od.\n	1pz	\N	\N	\N	1
97	CCS-REF-272-5\n	17	1	2	CONECTOR RECTO 	Material: Niquelado.\nDimensiones: De ½” npt a 3/8” od. 	1pz	\N	\N	\N	1
98	CCS-REF-411-9\n	34	1	2	TAPA PARA TANQUE D’CART. 	a	1pz	\N	\N	\N	1
99	CCS-REF-199-6\n	0	1	2	TAPÓN DE DRENAJE DPA 	Para tanque de 20 galones.\n	1pz	\N	\N	\N	1
100	CCS-REF-277-0\n	18	1	2	PIJA 	Material: Acero inoxidable serie 300.\nDimensiones: De 1/8” x 1 ¼”. 	2pz	\N	\N	\N	1
101	CCS-REF-361-4\n	20	1	2	CONECTOR RECTO 	Material: Acero inoxidable.\nDimensiones: De ½” od a ½” npt. 	2pz	\N	\N	\N	1
102	SN3	0	1	2	CONECTOR RÁPIDO 	Material: Acero inoxidable.\nDimensión: Macho ¼” a ¼” ntp hembra.\n	1pz	\N	\N	\N	1
103	CCS-REF-365-6\n	22	1	2	ARANDELA 	Material: Acero inoxidable.\nDiámetro interior: 9/16”.\nDiámetro exterior: 1”\nEspesor: 1/16”.\n	1pz	\N	\N	\N	1
104	CCS-REF-366-9\n	23	1	2	ARANDELA 	Material: Acero inoxidable.\nDiámetro interior: 7/8”.\nDiámetro exterior: 1 ½”.\nEspesor: 1/8”. 	1pz	\N	\N	\N	1
105	CCS-REF-319-154\n	0	1	2	MANGUERA KURIYAMA KIT INOXIDABLE 	Diámetro: ½”.\nDimensión: 10 metros.\nColor: Azul.\nModelo: k1136. 	10m	\N	\N	\N	1
106	CCS-REF-087-6\n	3	1	2	CONECTOR 	Material: Nylon.\nDimensión: ¼”.\nEspiga: Manguera 3/8”.	1pz	\N	\N	\N	1
107	CCS-REF-386-2\n	25	1	2	ETIQUETA D’CART 20 DUPLEX 	Material: Vinil.\n	1pz	\N	\N	\N	1
108	MPQ-D270-3\n	0	1	2	ENVASE 	Material: Polietileno de alta densidad.\nCapacidad: 10 L.\nColor: Natural. 	1pz	\N	\N	\N	1
109	CCS-REF-427-9\n	27	1	2	MEDIO NIPLE 	Material: Acero inoxidable.\nDimensiones: De ¼” x 5”. 	1pz	\N	\N	\N	1
110	CCS-REF-257-7\n	14	1	2	CODO 	Material: Acero inoxidable.\nDimensión: ¼” npt. 	1pz	\N	\N	\N	1
111	CCS-REF-187-9\n	11	1	2	REDUCCIÓN BUSHING 	Material: Acero inoxidable.\nDimensión: De ½” npt a ¼” npt.\n	1pz	\N	\N	\N	1
112	CCS-REF-415-1\n	26	1	2	PIJA 	Material: Acero inoxidable 304.\nDimensiones: De 1/8” x 7/8”. 	1pz	\N	\N	\N	1
113	CCS-REF-743-9\n	36	1	2	RESORTE DE PROTECCIÓN PARA MANGUERA 	Material: Acero inoxidable 302.\nLargo de resorte : 15 cm.\nDiámetro del material: .098” ( +- .005”).\nCon pata de arrastre.\n	1pz	\N	\N	\N	1
114	CCS-REF-372-8\n	24	1	2	MANGUERA KURIYAMA	Diámetro: ½”.\nDimensión: 10 metros.\nColor: Azul.\nModelo: k1136. 	10 m	\N	\N	\N	1
115	CCS-REF-192-5\n	13	1	2	VÁLVULA BOLA 	Material: Acero inoxidable.\nDimensión: De ½”.\nPuerto reducido 	1pz	\N	\N	\N	1
116	CCS-REF-270-9\n	15	1	2	VÁLVULA CHECK 	Dimensión: 3/8” od.\nPresión: 150 psi.\n	1pz	\N	\N	\N	1
117	CCS-REF-271-2\n	16	1	2	VÁLVULA DE CONTROL DE FLUJO 	Dimensión: 3/8” od legris\n	1pz	\N	\N	\N	1
118	CCS-REF-281-4\n	19	1	2	CONECTOR RECTO 	Material: Niquelado\nDimensiones: De 1/4” npt a 3/8” od	1pz	\N	\N	\N	1
119	CCS-REF-362-7\n	21	1	2	CONECTOR RÁPIDO MACHO	Material: Acero inoxidable.\nDimensión: Macho ¼” a ¼” ntp hembra\n	1pz	\N	\N	\N	1
120	CCS-REF-474-7\n	29	1	2	FILTRO PARA BOMBA 	Malla 40	1pz	\N	\N	\N	1
121	CCS-REF-479-2\n	30	1	2	CASQUILLO PARA MANGUERA 	Material: Acero inoxidable.\nDimensión: 1/2” , largo 1 1/8”	2pz	\N	\N	\N	1
122	CCS-REF-486-5\n	31	1	2	ETIQUETA DE GARANTÍA  TIPO HOLOGRAMA	a	2pz	\N	\N	\N	1
123	CCS-REF-520-1\n	32	1	2	Ayuda visual d'cart duplex\n	a	1pz	\N	\N	\N	1
124	MPQ-D270-3\n	33	1	2	ENVASE 	Material: Color natural\nDimensión: 10 lts. 	1pz	\N	\N	\N	1
125	CCS-REF-365-6\n	20	3	4	ARANDELA 	Material: Acero inoxidable.\nDiámetro interior: 9/16”.\nDiámetro exterior: 1”\nEspesor: 1/16”.\n	1pz	\N	\N	\N	1
126	CCS-REF-475-0\n	0	3	4	AYUDA VISUAL 	Material: Vinil en PVC.\nDimensiones: 30 x 45 cm.\n	1pz	\N	\N	\N	1
127	CCS-REF-486-5\n	31	3	4	ETIQUETA DE SEGURIDAD 	Material: Holograma metalizado.\n	2pz	\N	\N	\N	1
128	CCS-REF-281-4\n	30	3	4	CONECTOR RECTO 	Material: Niquelado.\nDimensiones: De ¼” npt a 3/8” od.\n	1pz	\N	\N	\N	1
129	CCS-REF-087-6\n	4	3	4	CONECTOR 	Material: Nylon.\nDimensión: ¼”.\nEspiga: Manguera 3/8”.\n	1pz	\N	\N	\N	1
130	CCS-REF-415-1\n	25	3	4	PIJA 	Material: Acero inoxidable 304.\nDimensiones: De 1/8” x 7/8”.\n	1pz	\N	\N	\N	1
131	CCS-REF-371-5\n	22	3	4	ETIQUETA DE SEGURIDAD WALL FOAM 	Material: Vinil.\n	1pz	\N	\N	\N	1
132	CCS-REF-464-6\n	28	3	4	TORNILLO 	Material: Acero inoxidable.\nDimensiones: ¼” - 20 x 1 ¼” de largo.\nCabeza: De botón.\nPara llave Torx.\n	8pz	\N	\N	\N	1
133	CCS-038-4\n	1	3	4	RACK PARA MANGUERA 	Material: Acero inoxidable.\n	1pz	\N	\N	\N	1
134	CCS-REF-163-4\n	29	3	4	CODO 	Material: Nylon.\nDimensión: 3/8” od x 1/8” npt.\nColor: Negro.\n	1pz	\N	\N	\N	1
135	CCS-REF-742-6\n	32	3	4	RESORTE DE PROTECCIÓN PARA MANGUERA 	Material: Acero inoxidable 302.\nLargo de resorte : 15 cm.\nDiámetro del material: .098” ( +- .005”).\n6 vueltas en cono para presión.\n	1pz	\N	\N	\N	1
136	CCS-REF-375-7\n	24	3	4	Espiga para manguera\n	 de 1/2" a 1/2" npt maquinada acero inoxidable 	2pz	\N	\N	\N	1
137	CCS-REF-475-0\n	26	3	4	Ayuda visual wall foam\n	0	1pz	\N	\N	\N	1
138	CCS-REF-479-2	27	3	4	Casquillo para manguera	1/2" acero inoxidable	2pz	\N	\N	\N	1
139	CCS-REF-106-4\n	6	3	5	NIPLE 	Material: Acero inoxidable T304.\nDiámetro: ¼”.\nDimensión: 3”.\n	1pz	\N	\N	\N	1
140	CCS-REF-187-9\n	11	3	5	REDUCCIÓN BUSHING 	Material: Acero inoxidable.\nDimensión: De ½” npt a ¼” npt.\n	1pz	\N	\N	\N	1
141	CCS-REF-366-9\n	21	3	5	ARANDELA 	Material: Acero inoxidable.\nDiámetro interior: 7/8”.\nDiámetro exterior: 1 ½”.\nEspesor: 1/8”.\n	1pz	\N	\N	\N	1
142	CCS-REF-362-7\n	19	3	5	CONECTOR RÁPIDO 	Material: Acero inoxidable.\nDimensión: Macho ¼” a ¼” npt hembra.\n	1pz	\N	\N	\N	1
143	CCS-REF-044-1\n	3	3	5	BOMBA FLOJET 	Material: Polipropileno.\nDoble diafragma.\n	1pz	\N	\N	\N	1
144	CCS-REF-162-1\n	7	3	5	REGULADOR 	Rango: 0-150 bs e y s1 / ae man 1/8” b63-02a.\nFiltro ¼” con purga automática.\n	1pz	\N	\N	\N	1
145	CCS-REF-166-3\n	8	3	5	TEE 	Material: Acero inoxidable T304.\nDimensión: De ½” npt.\n	1pz	\N	\N	\N	1
146	CCS-REF-167-6\n	9	3	5	TUBING 	Material: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	0.40m	\N	\N	\N	1
147	CCS-REF-168-9\n	10	3	5	TUBING 	Material: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	0.80m	\N	\N	\N	1
148	CCS-REF-761-6\n	12	3	5	VÁLVULA BOLA 	Material: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo.\n	1pz	\N	\N	\N	1
149	CCS-REF-019-1\n	2	3	5	FILTRO 	Material: Tela acero inoxidable.\nDimensión: ¼” hembra npt.\n	1pz	\N	\N	\N	1
150	CCS-REF-270-9\n	14	3	5	VÁLVULA CHECK 	Dimensión: 3/8” od.\nPresión: 175 psi.\n	1pz	\N	\N	\N	1
151	CCS-REF-271-2\n	15	3	5	VÁLVULA DE CONTROL DE FLUJO 	\nDimensión: 3/8” od.\n	1pz	\N	\N	\N	1
152	CCS-REF-272-5\n	16	3	5	CONECTOR RECTO 	\nMaterial: Niquelado.\nDimensiones: De ½” npt a 3/8” od.\n	1pz	\N	\N	\N	1
153	CCS-REF-277-0\n	17	3	5	PIJA 	\nMaterial: Acero inoxidable serie 300.\nDimensiones: De 1/8” x 1 ¼”.\n	2pz	\N	\N	\N	1
154	CCS-REF-319-154\n	0	3	5	MANGUERA KURIYAMA KIT INOXIDABLE 	\nDiámetro: ½”.\nDimensión: 10 metros.\nColor: Azul.\nModelo: k1136.\n	1pz	\N	\N	\N	1
155	CCS-REF-427-9\n	0	3	5	MEDIO NIPLE 	\nMaterial: Acero inoxidable.\nDimensiones: De ¼” x 5”.\n	1pz	\N	\N	\N	1
156	CCS-REF-092-2\n	5	3	5	MANGUERA TRAMADA 	\nMaterial: PVC flexible.\nDimensión: 3/8”.\n	1.5m	\N	\N	\N	1
157	CCS-REF-361-4\n	18	3	5	CONECTOR RECTO 	\nMaterial: Acero inoxidable.\nDimensiones: De ½” od a ½” npt.\n	1pz	\N	\N	\N	1
158	CCS-REF-365-6\n	20	3	5	ARANDELA 	\nMaterial: Acero inoxidable.\nDiámetro interior: 9/16”.\nDiámetro exterior: 1”\nEspesor: 1/16”.\n	1pz	\N	\N	\N	1
159	CCS-REF-475-0\n	26	3	5	AYUDA VISUAL 	\nMaterial: Vinil en PVC.\nDimensiones: 30 x 45 cm.\n	1pz	\N	\N	\N	1
160	CCS-REF-486-5\n	31	3	5	ETIQUETA DE SEGURIDAD 	\nMaterial: Holograma metalizado.\n	2pz	\N	\N	\N	1
161	CCS-REF-281-4\n	30	3	5	CONECTOR RECTO 	\nMaterial: Niquelado.\nDimensiones: De ¼” npt a 3/8” od.\n	1pz	\N	\N	\N	1
162	CCS-REF-087-6\n	0	3	5	CONECTOR 	\nMaterial: Nylon.\nDimensión: ¼”.\nEspiga: Manguera 3/8”.\n	1pz	\N	\N	\N	1
163	CCS-REF-415-1\n	25	3	5	PIJA 	\nMaterial: Acero inoxidable 304.\nDimensiones: De 1/8” x 7/8”.\n	2pz	\N	\N	\N	1
164	CCS-REF-371-5\n	22	3	5	ETIQUETA DE SEGURIDAD WALL FOAM 	\nMaterial: Vinil.\n	1pz	\N	\N	\N	1
165	CCS-REF-464-6\n	28	3	5	TORNILLO 	\nMaterial: Acero inoxidable.\nDimensiones: ¼” - 20 x 1 ¼” de largo.\nCabeza: De botón.\nPara llave Torx.\n	8pz	\N	\N	\N	1
166	CCS-038-4\n	1	3	5	RACK PARA MANGUERA 	\nMaterial: Acero inoxidable.\n	1pz	\N	\N	\N	1
167	CCS-REF-742-6\n	32	3	5	RESORTE DE PROTECCIÓN PARA MANGUERA 	\nMaterial: Acero inoxidable 302.\nLargo de resorte : 15 cm.\nDiámetro del material: .098” ( +- .005”).\n6 vueltas en cono para presión.\n	1pz	\N	\N	\N	1
168	CCS-REF-163-4\n	29	3	5	CODO 	\nMaterial: Nylon.\nDimensión: 3/8” od x 1/8” npt.\nColor: Negro.\n	1pz	\N	\N	\N	1
169	CCS-REF-372-8\n	23	3	5	Manguera kuriyama\n	1/2" azul k1136 	10m	\N	\N	\N	1
170	CCS-REF-375-7\n	24	3	5	Espiga para manguera\n	de 1/2" a 1/2" npt maquinada acero inoxidable 	2pz	\N	\N	\N	1
171	CCS-REF-525-6\n	23	3	5	Ayuda visual mini wall foam\n	a	1pz	\N	\N	\N	1
172	CCS-REF-486-5\n	25	3	5	Etiqueta de garantia tipo holograma\n	a	2pz	\N	\N	\N	1
173	CCS-REF-025-1\n	2	4	6	NIPLE 	\nMaterial: Bronce.\nDimensiones: De ¼” x 1”.\nRosca: Corrida.\n	1pz	\N	\N	\N	1
174	CCS-REF-019-1\n	1	4	6	FILTRO 	\nMaterial: Tela acero inoxidable.\nDimensión: ¼” hembra npt.\n	1pz	\N	\N	\N	1
175	CCS-REF-044-1\n	3	4	6	BOMBA FLOJET 	\nMaterial: Polipropileno.\nDoble diafragma.\n	1pz	\N	\N	\N	1
176	CCS-REF-087-6\n	10	4	6	CONECTOR 	\nMaterial: Nylon.\nDimensión: ¼”.\nEspiga: Manguera 3/8”.\n	1pz	\N	\N	\N	1
177	CCS-REF-162-1\n	14	4	6	REGULADOR 	\nRango: 0-150 bs e y s1 / ae man 1/8” b63-02a.\nFiltro ¼” con purga automática.\n	1pz	\N	\N	\N	1
178	CCS-REF-166-3\n	15	4	6	TEE 	\nMaterial: Acero inoxidable T304.\nDimensión: De ½” npt.\n	1pz	\N	\N	\N	1
179	CCS-REF-168-9\n	17	4	6	TUBING 	\nMaterial: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	0.70m	\N	\N	\N	1
180	CCS-REF-167-6\n	16	4	6	TUBING 	\nMaterial: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	0.40m	\N	\N	\N	1
181	CCS-REF-270-9\n	22	4	6	VÁLVULA CHECK 	\nDimensión: 3/8” od.\nPresión: 150 psi.\n	1pz	\N	\N	\N	1
182	CCS-REF-271-2\n	23	4	6	VÁLVULA DE CONTROL DE FLUJO 	\nDimensiones: 3/8” od.\n	1pz	\N	\N	\N	1
183	CCS-REF-272-5\n	24	4	6	CONECTOR RECTO 	\nMaterial: Niquelado.\nDimensiones: De ½” npt a 3/8” od.\n	1pz	\N	\N	\N	1
184	CCS-REF-277-0\n	25	4	6	PIJA 	\nMaterial: Acero inoxidable serie 300.\nDimensiones: De 1/8” x 1 ¼”.\n	3pz	\N	\N	\N	1
185	CCS-REF-092-2\n	11	4	6	MANGUERA TRAMADA 	\nMaterial: PVC flexible.\nDimensión: 3/8”.\n	1.5m	\N	\N	\N	1
186	CCS-REF-361-4\n	26	4	6	CONECTOR RECTO 	\nMaterial: Acero inoxidable.\nDimensiones: De ½” od a ½” npt.\n	1pz	\N	\N	\N	1
187	CCS-REF-362-7\n	27	4	6	CONECTOR RÁPIDO 	\nMaterial: Acero inoxidable.\nDimensión: Macho ¼” a ¼” npt hembra.\n	1pz	\N	\N	\N	1
188	CCS-REF-365-6\n	28	4	6	ARANDELA 	\nMaterial: Acero inoxidable.\nDiámetro interior: 9/16”.\nDiámetro exterior: 1”\nEspesor: 1/16”.\n	1pz	\N	\N	\N	1
189	CCS-REF-366-9\n	29	4	6	ARANDELA 	\nMaterial: Acero inoxidable.\nDiámetro interior: 7/8”.\nDiámetro exterior: 1 ½”.\nEspesor: 1/8”.\n	1pz	\N	\N	\N	1
190	CCS-REF-415-1\n	31	4	6	PIJA 	\nMaterial: Acero inoxidable 304.\nDimensiones: De 1/8” x 7/8”.\n	2pz	\N	\N	\N	1
191	CCS-REF-060-2\n	4	4	6	ESPREA 	\nMaterial: Bronce.\nDimensión: Rosca macho ¼” npt.\n	2pz	\N	\N	\N	1
192	CCS-REF-066-0\n	5	4	6	APAGADOR ON/OFF 	\nMaterial: Plástico.\n250 volts amps.\n	1pz	\N	\N	\N	1
193	CCS-REF-067-3\n	6	4	6	CABLE USO RUDO 	\nHilos: 3.\nCalibre: 14.\nColor: Gris.\n	2.5m	\N	\N	\N	1
194	CCS-REF-069-9\n	7	4	6	VÁLVULA SOLENOIDE 	\nMaterial: Bronce.\nDimensión: Rosca hembra ¼”.\n127 VAC.\n	1pz	\N	\N	\N	1
195	CCS-REF-070-3\n	8	4	6	TIMER ELECTRÓNICO 	\nModelo: KRDR421A4.\n2 funciones.\n	1pz	\N	\N	\N	1
196	CCS-REF-082-1\n	9	4	6	CLAVIJA POLARIZADA 	Voltaje: 127 VAC.\n	1pz	\N	\N	\N	1
197	CCS-REF-137-1\n	12	4	6	CONECTOR GLANDULA USO RUDO 	\nMaterial: Plástico.\nDimensión: ½”.\n	1pz	\N	\N	\N	1
198	CCS-REF-138-4\n	13	4	6	TERMINAL HEMBRA ESTÁNDAR 	\nCalibre: 14.\n	6pz	\N	\N	\N	1
199	CCS-REF-178-0\n	18	4	6	TEE UNION 	\nMaterial: Latón niquelado con cuerpo negro de nylon.\nDimensiones: ½” od a ½” npt.\n	1pz	\N	\N	\N	1
200	CCS-REF-180-8\n	20	4	6	TUBING 	\nMaterial: Polietileno.\nDimensión: ½”.\nColor: Blanco.\n	6pz	\N	\N	\N	1
201	CCS-REF-181-1\n	21	4	6	CODO UNION 	\nMaterial: Cuerpo negro de nylon.\nDimensión: ½” od.\n	2pz	\N	\N	\N	1
202	CCS-REF-371-67\n	30	4	6	ETIQUETA DOOR WAY 	\nMaterial: Vinil.\n	1pz	\N	\N	\N	1
203	CCS-REF-419-3\n	34	4	6	TEE UNION 	\nMaterial: Latón niquelado con cuerpo negro de nylon.\nDimensiones: 3/8” od a ¼” npt.\n	1pz	\N	\N	\N	1
204	CCS-REF-464-6\n	32	4	6	TORNILLO 	\nMaterial: Acero inoxidable.\nDimensión: 1 ¼” - 20 x 1 ¼”.\nCabeza: De botón.\nPara llave Torx.\n	8pz	\N	\N	\N	1
205	CCS-REF-179-3\n	19	4	6	COPLE 	\nMaterial: Bronce.\nDimensión: ¼” npt.\n	2pz	\N	\N	\N	1
206	CCS-REF-485-2\n	35	4	6	AYUDA VISUAL 	\nMaterial: Vinil en PVC.\nDimensiones: 30 x 45 cm.\n	1pz	\N	\N	\N	1
207	CCS-REF-486-5\n	36	4	6	ETIQUETA DE SEGURIDAD 	\nMaterial: Holograma metalizado.\n	2pz	\N	\N	\N	1
208	CCS-REF-106-4\n	33	4	6	NIPLE 	\nMaterial: Acero inoxidable T304.\nDiámetro: ¼”.\nDimensión: 3”.\n	1pz	\N	\N	\N	1
209	CCS-038-4\n	1	4	7	RACK PARA MANGUERA 	\nMaterial: Acero inoxidable.\n	1pz	\N	\N	\N	1
210	CCS-REF-113-6\n	2	4	7	INYECTOR 	\nMaterial: Plástico.\nDimensión: 3/8” npt.\nModelo: #P203C.\n	1pz	\N	\N	\N	1
211	CCS-REF-457-3\n	12	4	7	MEDIO NIPLE 	\nMaterial: Acero inoxidable.\nDimensiones: 3/8” x 5”.\n	1pz	\N	\N	\N	1
212	CCS-REF-125-4\n	3	4	7	KIT ESPREAS 	\nModelo: Dema #100-15K (14 pz por kit).\n	1pz	\N	\N	\N	1
213	CCS-REF-761-6\n	4	4	7	VÁLVULA BOLA 	\nMaterial: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo.\n	1pz	\N	\N	\N	1
214	CCS-REF-319-154\n	0	4	7	MANGUERA KURIYAMA KIT INOXIDABLE 	\nDiámetro: ½”.\nDimensión: 10 metros.\nColor: Azul.\nModelo: k1136.\n	1pz	\N	\N	\N	1
215	CCS-REF-376-0\n	8	4	7	ESPREA 	\nMaterial: Acero inoxidable.\nDimensiones: De ¼” rosca macho npt.\nÁngulo: Abanico 15°.\n	1pz	\N	\N	\N	1
216	CCS-REF-247-6\n	5	4	7	CONEXIÓN PARA MANGUERA JARDINERA 	\nMaterial: Bronce.\nDimensión: 3/8”.\n	1pz	\N	\N	\N	1
217	CCS-REF-379-9\n	14	4	7	ETIQUETA DIAGRAMA MINI WALL SANITIZER 	\nMaterial: Vinil.\n	1pz	\N	\N	\N	1
218	CCS-REF-388-8\n	9	4	7	ETIQUETA MINI WALL SANITIZER 	\nMaterial: Vinil.\n	1pz	\N	\N	\N	1
219	CCS-REF-122-5\n	11	4	7	REDUCCION BUSHING 	\nMaterial: Acero inoxidable.\nDimensiones: De ½” npt a 3/8” npt.\n	1pz	\N	\N	\N	1
220	CCS-REF-366-9\n	10	4	7	ARANDELA 	\nMaterial: Acero inoxidable.\nDiámetro interior: 7/8”.\nDiámetro exterior: 1 ½”.\nEspesor: 1/8”.\n	1pz	\N	\N	\N	1
221	CCS-REF-742-6\n	19	4	7	RESORTE DE PROTECCIÓN PARA MANGUERA 	\nMaterial: Acero inoxidable 302.\nLargo de resorte : 15 cm.\nDiámetro del material: .098” ( +- .005”).\n6 vueltas en cono para presión.\n	1pz	\N	\N	\N	1
222	CCS-REF-448-5\n	13	4	7	COPLE ROSCADO 	\nMaterial: Acero inoxidable.\nDimensiones: 3/8” x 1”.\n	1pz	\N	\N	\N	1
223	CCS-REF-372-8\n	6	4	7	Manguera kuriyama\n	1/2" azul k1136 	10m	\N	\N	\N	1
224	CCS-REF-375-7\n	7	4	7	Espiga para manguera\n	de 1/2" a 1/2" npt maquinada acero inoxidable 	2pz	\N	\N	\N	1
225	CCS-REF-527-2\n	16	4	7	Ayuda visual mini wall sanitizer\n	a	1pz	\N	\N	\N	1
226	CCS-REF-486-5\n	18	4	7	Etiqueta de garantia tipo holograma\n	a	2pz	\N	\N	\N	1
227	CCS-REF-025-1\n	1	5	8	NIPLE 	\nMaterial: Bronce.\nDimensiones: De ¼” x 1”.\nRosca: Corrida.\n	6pz	\N	\N	\N	1
228	CCS-REF-473-4\n	29	5	8	BOMBA PARA BOSTER 	\nMaterial: Polipropileno.\nDoble diafragma.\n	1pz	\N	\N	\N	1
229	CCS-REF-166-3\n	2	5	8	TEE 	\nMaterial: Acero inoxidable T304.\nDimensión: De ½” npt.\n	1pz	\N	\N	\N	1
230	CCS-REF-163-4\n	3	5	8	CODO 	\nÁngulo: 90°.\nDimensiones: 3/8” od x 1/8” npt.\n	1pz	\N	\N	\N	1
231	CCS-REF-168-9\n	5	5	8	TUBING 	\nMaterial: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	0.80m	\N	\N	\N	1
232	CCS-REF-167-6\n	4	5	8	TUBING 	\nMaterial: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	1.30m	\N	\N	\N	1
233	CCS-REF-176-4\n	6	5	8	CONECTOR CODO 	\nMaterial: Latón niquelado.\nDimensión: ½” od a ½” npt.\n	2pz	\N	\N	\N	1
234	CCS-REF-177-7\n	7	5	8	CODO ROSCADO 	\nMaterial: Acero inoxidable.\nÁngulo: 90°.\nDimensión: De ½” npt.\n	1pz	\N	\N	\N	1
235	CCS-REF-190-9\n	8	5	8	CODO 	\nMaterial: Latón.\nDimensiones: Macho ¼” npt y hembra ¼”.\n	6pz	\N	\N	\N	1
236	CCS-REF-454-4\n	9	5	8	NIPLE 	\nMaterial: Acero inoxidable.\nDimensiones: De ½” npt x 71”.\n	1pz	\N	\N	\N	1
237	CCS-REF-258-0\n	10	5	8	MINIREGULADOR SIMPLE 	\nMaterial: Aluminio.\nDimensiones: Entrada y salida ¼” npt.\n	1pz	\N	\N	\N	1
238	CCS-REF-270-9\n	11	5	8	VÁLVULA CHECK 	\nDimensión: 3/8” od.\nPresión: 175 psi.\n	1pz	\N	\N	\N	1
239	CCS-REF-271-2\n	12	5	8	VÁLVULA DE CONTROL DE FLUJO 	\nDimensiones: 3/8” od.\n	1pz	\N	\N	\N	1
240	CCS-REF-272-5\n	13	5	8	CONECTOR RECTO 	\nMaterial: Niquelado.\nDimensiones: De ½” npt a 3/8” od.\n	1pz	\N	\N	\N	1
241	CCS-REF-277-0\n	14	5	8	PIJA 	\nMaterial: Acero inoxidable serie 300.\nDimensiones: De 1/8” x 1 ¼”.\n	4pz	\N	\N	\N	1
242	CCS-REF-281-4\n	15	5	8	CONECTOR RECTO 	\nMaterial: Niquelado.\nDimensiones: De ¼” npt a 3/8” od.\n	1pz	\N	\N	\N	1
243	CCS-REF-295-7\n	16	5	8	ABRAZADERA OMEGA 	\nMaterial: Acero inoxidable.\nDimensiones: 1” ancho para tubo de ½”.\n	1pz	\N	\N	\N	1
244	CCS-REF-360-18\n	17	5	8	CONECTOR RÁPIDO 	\nMaterial: Acero inoxidable.\nDimensión: ¼” macho npt.\n	1pz	\N	\N	\N	1
245	CCS-REF-361-4\n	18	5	8	CONECTOR RECTO 	\nMaterial: Acero inoxidable.\nDimensiones: De ½” od a ½” npt.\n	1pz	\N	\N	\N	1
246	CCS-REF-365-6\n	19	5	8	ARANDELA 	\nMaterial: Acero inoxidable.\nDiámetro interior: 9/16”.\nDiámetro exterior: 1”\nEspesor: 1/16”.\n	1pz	\N	\N	\N	1
247	CCS-REF-366-9\n	20	5	8	ARANDELA 	\nMaterial: Acero inoxidable.\nDiámetro interior: 7/8”.\nDiámetro exterior: 1 ½” .\nEspesor: 1/8”.\n	1pz	\N	\N	\N	1
248	CCS-REF-472-1\n	28	5	8	TORNILLO 	\nMaterial: Acero inoxidable 304.\nDimensión: De ¼” - 20 x ¾” de largo.\nCabeza: Plana.\nPara llave Torx.\n	2pz	\N	\N	\N	1
249	CCS-REF-380-40\n	21	5	8	ETIQUETA 6 FOGGER 	\nMaterial: Vinil.\n	1pz	\N	\N	\N	1
250	CCS-REF-199-6\n	0	5	8	TAPÓN DE DRENAJE DPA 	\nPara tanque de 20 galones.\n	1pz	\N	\N	\N	1
251	CCS-REF-397-6\n	22	5	8	BOQUILLA DE CONO HUECO 	\nMaterial: Polipropileno.\nColor: Verde.\nNúmero: 1.5.\n	6pz	\N	\N	\N	1
252	CCS-REF-405-0\n	24	5	8	CUERPO HEMBRA 	\nMaterial: Bronce.\nDimensión: ¼” npt x 11/16”.\n	6pz	\N	\N	\N	1
253	CCS-REF-403-4\n	0	5	8	TUERCA 	\nMaterial: Bronce.\nDimensión: 11/16”.\n	1pz	\N	\N	\N	1
254	CCS-REF-415-1\n	25	5	8	PIJA 	\nMaterial: Acero inoxidable 304.\nDimensión: De 1/8” x 7/8”.\n	4pz	\N	\N	\N	1
255	CCS-REF-440-1\n	26	5	8	MANOMETRO 	\nRango: 0 - 100 psi.\nDimensión: De 1/8” npt.\nDimensión carátula: De 1 ½”.\nTrasero.\n	1pz	\N	\N	\N	1
256	CCS-REF-486-5\n	31	5	8	ETIQUETA DE SEGURIDAD 	\nMaterial: Holograma metalizado.\n	2pz	\N	\N	\N	1
257	CCS-REF-411-9\n	32	5	8	TAPA PARA TANQUE D’CART. 	0	1pz	\N	\N	\N	1
258	CCS-REF-483-6\n	30	5	8	AYUDA VISUAL 	\nMaterial: Vinil en PVC.\nDimensiones: 27.5 x 21.5 cm.\n	1pz	\N	\N	\N	1
259	CCS-REF-474-7\n	27	5	8	FILTRO PARA BOMBA 	\nMaterial: Plástico.\nDimensión: Conectores de 3/8”.\n	1pz	\N	\N	\N	1
260	CCS-REF-403-0\n	23	5	8	Tuerca	bronce 11/16\n	6pz	\N	\N	\N	1
261	CCS-REF-199-6\n	33	5	8	Tapón de drenaje\n	dpa p/ tanque 20 galones 	1pz	\N	\N	\N	1
262	CCS-REF-431-2\n	1	6	9	MANGUERA KURIYAMA 	\nDiámetro: 3/8”.\nDimensión: 15 metros.\nColor: Azul.\nModelo: k1136.\n	15m	\N	\N	\N	1
263	CCS-REF-319-15\n	0	6	9	MANGUERA KURIYAMA KIT INOXIDABLE 	\nDiámetro: ½”.\nDimensión: 15 metros.\nColor: Azul.\n	1pz	\N	\N	\N	1
264	CCS-REF-761-6\n	4	6	9	VÁLVULA BOLA 	\nMaterial: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo.\n	1pz	\N	\N	\N	1
265	CCS-REF-433-8\n	3	6	9	VÁLVULA 	\nMaterial: Acero inoxidable.\nDimensión: De ¼” npt.\n	1pz	\N	\N	\N	1
266	CCS-REF-376-0\n	8	6	9	ESPREA 	\nMaterial: Acero inoxidable.\nDimensiones: De ¼” rosca macho npt.\nÁngulo: Abanico 15°.\n	1pz	\N	\N	\N	1
267	CCS-REF-187-9\n	10	6	9	REDUCCIÓN BUSHING 	\nMaterial: acero inoxidable.\nDe ½” npt a ¼” npt.\n	1pz	\N	\N	\N	1
268	CCS-REF-364-3\n	7	6	9	COPLE ROSCADO 	\nMaterial: Acero inoxidable.\nDimensiones: De ¼” npt.\n	1pz	\N	\N	\N	1
269	CCS-REF-432-5\n	5	6	9	ESPIGA PARA MANGUERA 	\nMaterial: Acero inoxidable.\nDimensión: De 3/8” a ¼” npt macho.\n	2pz	\N	\N	\N	1
270	CCS-REF-427-9\n	9	6	9	MEDIO NIPLE 	\nMaterial: Acero inoxidable.\nDimensiones: De ¼” x 5”.\n	2pz	\N	\N	\N	1
271	CCS-REF-742-6\n	13	6	9	RESORTE DE PROTECCIÓN PARA MANGUERA 	\nMaterial: Acero inoxidable 302.\nLargo de resorte : 15 cm.\nDiámetro del material: .098” ( +- .005”).\n6 vueltas en cono para presión.\n	1pz	\N	\N	\N	1
272	CCS-REF-375-7\n	6	6	9	Espiga para manguera	de ½” acero inoxidable a ½” npt\n	2pz	\N	\N	\N	1
273	CCS-REF-479-2\n	11	6	9	Casquillo para manguera\n	 ½” acero inoxidable 	2pz	\N	\N	\N	1
274	CCS-REF-480-7\n	12	6	9	Casquillo para manguera	3/8” acero inoxidable\n	2pz	\N	\N	\N	1
275	CCS-REF-158-7\n	1	7	13	ADITAMENTO LYSTER QUAT 	\nMaterial: Acero inoxidable.\nDiámetro: 7”.\n	1pz	\N	\N	\N	1
276	CCS-REF-159-0\n	7	7	13	ADITAMENTO LYSTER QUAT 	\nMaterial: Acero inoxidable.\nDiámetro: 9”.\n	1pz	\N	\N	\N	1
277	CCS-REF-160-5\n	8	7	13	ADITAMENTO LYSTER QUAT ROSSER 	\nMaterial: Acero inoxidable.\nDimensiones: 25 x 30 cm.\n	1pz	\N	\N	\N	1
278	CCS-REF-166-3\n	2	7	13	TEE 	\nMaterial: Acero inoxidable T304.\nDimensión: De ½” npt.\n	1pz	\N	\N	\N	1
279	CCS-REF-173-5\n	3	7	13	HULE ESPONJA 	\nMaterial: EPDM Etileno Propileno.\nDimensiones: ½” x 1”.\nColor: Negro.\n	0.70m                 0.90                  1.10m	\N	\N	\N	1
280	CCS-REF-195-4\n	4	7	13	NIPLE 	\nMaterial: Acero inoxidable.\nDimensiones: ½” x 90 cm.\n	1pz	\N	\N	\N	1
281	CCS-REF-204-1\n	5	7	13	TAPON CACHUCHA 	\nMaterial: Acero inoxidable.\nDimensiones: ½”.\n	1pz	\N	\N	\N	1
282	CCS-REF-208-3\n	6	7	13	MEDIO NIPLE 	\nMaterial: Acero inoxidable T304.\nDimensiones: ½” x 5”.\nCédula: Estándar.\n	2pz	\N	\N	\N	1
283	CCS-REF-019-1\n	1	8	13	FILTRO 	\nMaterial: Tela acero inoxidable.\nDimensión: ¼” hembra npt.\n	1pz	\N	\N	\N	1
284	CCS-REF-044-1\n	2	8	13	BOMBA FLOJET 	\nMaterial: Polipropileno.\nDoble diafragma.\n	1pz	\N	\N	\N	1
285	CCS-REF-087-6\n	3	8	13	CONECTOR 	\nMaterial: Nylon.\nDimensión: ¼”.\nEspiga: Manguera 3/8”.\n	1pz	\N	\N	\N	1
286	CCS-REF-092-2\n	4	8	13	MANGUERA TRAMADA 	\nMaterial: PVC flexible.\nDimensión: 3/8”.\n	2m	\N	\N	\N	1
287	CCS-REF-440-1\n	13	8	13	MANOMETRO 	\nRango: 0 - 100 psi.\nDimensión: De 1/8” npt.\nDimensión carátula: De 1½”.\nTrasero.\n	1pz	\N	\N	\N	1
288	CCS-REF-167-6\n	6	8	13	TUBING 	\nMaterial: Poliuretano.\nDimensiones: De ½” od x 0.090.\nPresión: 175 psi.\nColor: Transparente.\nMetro lineal.\n	1.50m	\N	\N	\N	1
289	CCS-REF-168-9\n	7	8	13	TUBING 	\nMaterial: Poliuretano.\nDimensiones: De 3/8” od x 0.065.\nPresión: 170 psi.\nColor: Transparente.\nMetro lineal.\n	0.40m	\N	\N	\N	1
290	CCS-REF-761-6\n	8	8	13	VÁLVULA BOLA 	\nMaterial: Acero inoxidable.\nDimensión: De ½”.\nPuerto completo.\n	1pz	\N	\N	\N	1
291	CCS-REF-361-4\n	9	8	13	CONECTOR RECTO 	\nMaterial: Acero inoxidable.\nDimensiones: De ½” od a ½” npt.\n	2pz	\N	\N	\N	1
292	CCS-REF-106-4\n	10	8	13	NIPLE 	\nMaterial: Acero inoxidable T304.\nDiámetro: ¼”.\nDimensión: 3”.\n	1pz	\N	\N	\N	1
293	CCS-REF-362-7\n	11	8	13	CONECTOR RÁPIDO 	\nMaterial: Acero inoxidable.\nDimensión: Macho ¼” a ¼” npt hembra.\n	1pz	\N	\N	\N	1
294	CCS-REF-415-1\n	15	8	13	PIJA 	\nMaterial: Acero inoxidable 304.\nDimensión: De 1/8” x 7/8”.\n	2pz	\N	\N	\N	1
295	CCS-REF-441-4\n	14	8	13	ETIQUETA CHEMICAL TRANSFER 	\nMaterial: Vinil.\n	1pz	\N	\N	\N	1
296	CCS-REF-258-0\n	5	8	13	MINIREGULADOR SIMPLE 	\nMaterial: Aluminio.\nDimensiones: Entrada y salida ¼” npt.\n	1pz	\N	\N	\N	1
297	CCS-REF-163-4\n	12	8	13	CODO 	\nÁngulo: 90°.\nDimensiones: 3/8” od x 1/8” npt.\n	1pz	\N	\N	\N	1
298	CCS-REF-521-4\n	16	8	13	Ayuda visual chemical transfer\n	a	1pz	\N	\N	\N	1
299	CCS-REF-486-5\n	18	8	13	Etiqueta de garantia tipo holograma\n	a	2pz	\N	\N	\N	1
300	CCS-REF-044-1 	0	9	14	 BOMBA FLOJET  	a	10 pz	\N	\N	\N	1
301	CCS-REF-162-1	0	9	14	REGULADOR	a	10pz	\N	\N	\N	1
302	CCS-REF-199-6	0	9	14	TAPÓN DE DRENAJE DPA  	a	5pz	\N	\N	\N	1
303	CCS-REF-168-9	0	9	14	TUBING	a	20m	\N	\N	\N	1
304	CCS-REF319-154	0	9	14	MANGUERA KURIYAMA KIT INOXIDABLE	a	10kit	\N	\N	\N	1
305	CCS-REF-270-9 	0	9	14	VÁLVULA CHECK	a	10pz	\N	\N	\N	1
306	CCS-REF-375-7 	0	9	14	Espiga para manguera	 de 1/2" a 1/2" npt maquinada acero inoxidable 	10pz	\N	\N	\N	1
307	CCS-FRF- 187-9  	0	9	14	álvula bola	 de ½ inox 	10pz	\N	\N	\N	1
308	ccs-ref- 187-9 	0	9	14	Reducción bushing  	de ½ a ¼ inox 	10pz	\N	\N	\N	1
309	CCS-REF-427-9 	0	9	14	 medio niple   	de ¼ inox	10pz	\N	\N	\N	1
\.


--
-- Data for Name: report_services; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.report_services (id, service_id, product_user_id, costs, costs_repairs, subtotal, total, progress, description, status, dilution, frequency, method, service_end, service_start, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: services; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.services (id, client_id, technical_id, tentative_date, branch_office_id, created_at, updated_at, deleted_at, kms, type, activity) FROM stdin;
\.


--
-- Data for Name: todos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.todos (id, description, type, activity, date, kms, technical_id, client_id, status, deleted_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, last_name, second_last_name, activities, remember_token, created_at, updated_at, deleted_at, role, branch_office_id, business_name, rfc, company_name, contacts) FROM stdin;
3	tecnico	ccs.celaya@dikeninternational.com	\N	$2y$10$8QIG50xzlnCFIRY8BitjwO.InmKOAUgOSlj27S8xs9ArgSRtPAxI2	\N	\N	[]	\N	2020-09-16 09:17:29	2020-09-16 09:17:29	\N	tecnico	1	\N	\N	\N	[]
2	Sergio	administrador@diken.com	\N	$2y$10$KUHtFSMF4SGrAUSZ9wZdUe05TbNpnKsMgJ0S12M4FTj5sd2dE3Zuu	\N	\N	[]	\N	2020-09-16 09:17:29	2020-09-21 13:28:28	\N	admin	1	\N	\N	\N	[]
1	Administrator	admin@admin.com	\N	$2y$10$cLOjeuu3aEk.XMdgawDI8urrh0uO4wGMu7qrNGIzoIccfAp1LDyxK	\N	\N	[]	\N	2020-09-16 09:17:29	2020-09-21 13:28:21	\N	admin	1	\N	\N	\N	[]
\.


--
-- Name: branch_offices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.branch_offices_id_seq', 1, true);


--
-- Name: category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.category_id_seq', 1, true);


--
-- Name: category_repair_parts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.category_repair_parts_id_seq', 9, true);


--
-- Name: files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.files_id_seq', 1, false);


--
-- Name: messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.messages_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 20, true);


--
-- Name: product_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.product_user_id_seq', 1, false);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_id_seq', 1, false);


--
-- Name: products_repair_parts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_repair_parts_id_seq', 14, true);


--
-- Name: repair_parts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.repair_parts_id_seq', 309, true);


--
-- Name: report_services_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.report_services_id_seq', 1, false);


--
-- Name: services_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.services_id_seq', 1, false);


--
-- Name: todos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.todos_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 3, true);


--
-- Name: branch_offices branch_offices_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.branch_offices
    ADD CONSTRAINT branch_offices_pkey PRIMARY KEY (id);


--
-- Name: category category_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- Name: category_repair_parts category_repair_parts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_repair_parts
    ADD CONSTRAINT category_repair_parts_pkey PRIMARY KEY (id);


--
-- Name: files files_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files
    ADD CONSTRAINT files_pkey PRIMARY KEY (id);


--
-- Name: messages messages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: product_user product_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_user
    ADD CONSTRAINT product_user_pkey PRIMARY KEY (id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: products_repair_parts products_repair_parts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products_repair_parts
    ADD CONSTRAINT products_repair_parts_pkey PRIMARY KEY (id);


--
-- Name: repair_parts repair_parts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.repair_parts
    ADD CONSTRAINT repair_parts_pkey PRIMARY KEY (id);


--
-- Name: report_services report_services_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_services
    ADD CONSTRAINT report_services_pkey PRIMARY KEY (id);


--
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (id);


--
-- Name: todos todos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.todos
    ADD CONSTRAINT todos_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: messages messages_author_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_author_id_foreign FOREIGN KEY (author_id) REFERENCES public.users(id);


--
-- Name: messages messages_branch_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_branch_office_id_foreign FOREIGN KEY (branch_office_id) REFERENCES public.branch_offices(id);


--
-- Name: messages messages_services_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_services_id_foreign FOREIGN KEY (services_id) REFERENCES public.services(id);


--
-- Name: product_service product_service_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_service
    ADD CONSTRAINT product_service_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id);


--
-- Name: product_service product_service_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_service
    ADD CONSTRAINT product_service_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(id);


--
-- Name: product_user product_user_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_user
    ADD CONSTRAINT product_user_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id);


--
-- Name: product_user product_user_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_user
    ADD CONSTRAINT product_user_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: products products_branch_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_branch_office_id_foreign FOREIGN KEY (branch_office_id) REFERENCES public.branch_offices(id);


--
-- Name: products products_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.category(id);


--
-- Name: repair_parts repair_parts_branch_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.repair_parts
    ADD CONSTRAINT repair_parts_branch_office_id_foreign FOREIGN KEY (branch_office_id) REFERENCES public.branch_offices(id);


--
-- Name: repair_parts repair_parts_category_repair_parts_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.repair_parts
    ADD CONSTRAINT repair_parts_category_repair_parts_id_foreign FOREIGN KEY (category_repair_parts_id) REFERENCES public.category_repair_parts(id);


--
-- Name: repair_parts repair_parts_product_repair_parts_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.repair_parts
    ADD CONSTRAINT repair_parts_product_repair_parts_id_foreign FOREIGN KEY (product_repair_parts_id) REFERENCES public.products_repair_parts(id);


--
-- Name: report_services report_services_product_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_services
    ADD CONSTRAINT report_services_product_user_id_foreign FOREIGN KEY (product_user_id) REFERENCES public.product_user(id);


--
-- Name: report_services report_services_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_services
    ADD CONSTRAINT report_services_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(id);


--
-- Name: services services_branch_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_branch_office_id_foreign FOREIGN KEY (branch_office_id) REFERENCES public.branch_offices(id);


--
-- Name: todos todos_client_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.todos
    ADD CONSTRAINT todos_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.users(id);


--
-- Name: todos todos_technical_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.todos
    ADD CONSTRAINT todos_technical_id_foreign FOREIGN KEY (technical_id) REFERENCES public.users(id);


--
-- Name: users users_branch_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_branch_office_id_foreign FOREIGN KEY (branch_office_id) REFERENCES public.branch_offices(id);


--
-- PostgreSQL database dump complete
--

