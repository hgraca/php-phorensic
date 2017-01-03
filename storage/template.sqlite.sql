CREATE TABLE files
(
    `path` TEXT PRIMARY KEY NOT NULL,   -- The relative file path
    `type` TEXT,                        -- The file type, AKA extension
    `commits` INTEGER DEFAULT 0,        -- The amount of commits that changed this file
    `active_days` INTEGER DEFAULT 0,    -- The amount of days with commits that changed this file

    `ca` INTEGER DEFAULT NULL,          -- Afferent Coupling - Number of unique incoming dependencies from other artifacts of the same type.
    `cbo` INTEGER DEFAULT NULL,         -- Coupling Between Objects - Number of unique outgoing dependencies to other artifacts of the same type.
    `ce` INTEGER DEFAULT NULL,          -- Efferent Coupling - Number of unique outgoing dependencies to other artifacts of the same type.
    `cloc` INTEGER DEFAULT NULL,        -- Comment Lines of Code
    `cis` INTEGER DEFAULT NULL,         -- Class Interface Size
    `cr` REAL DEFAULT NULL,             -- Code Rank - Classes with a high value should be tested frequently.
    `csz` INTEGER DEFAULT NULL,         -- Class Size
    `dit` INTEGER DEFAULT NULL,         -- Depth of Inheritance Tree
    `eloc` INTEGER DEFAULT NULL,        -- Executable Lines of Code
    `impl` INTEGER DEFAULT NULL,        --
    `lloc` INTEGER DEFAULT NULL,        -- Logical Lines Of Code
    `loc` INTEGER DEFAULT NULL,         -- Lines Of Code
    `ncloc` INTEGER DEFAULT NULL,       -- Non Comment Lines Of Code
    `noam` INTEGER DEFAULT NULL,        -- Number Of Added Methods
    `nocc` INTEGER DEFAULT NULL,        -- Number Of Child Classes
    `nom` INTEGER DEFAULT NULL,         --
    `noom` INTEGER DEFAULT NULL,        -- Number Of Overwritten Methods
    `npm` INTEGER DEFAULT NULL,         -- Number of Public Methods
    `rcr` REAL DEFAULT NULL,            -- Reverse Code Rank
    `vars` INTEGER DEFAULT NULL,        -- Properties
    `varsi` INTEGER DEFAULT NULL,       -- Inherited Properties
    `varsnp` INTEGER DEFAULT NULL,      -- Non Private Properties
    `wmc` INTEGER DEFAULT NULL,         -- Weighted Method Count - The sum of the complexities of all declared methods and constructors of class.
    `wmci` INTEGER DEFAULT NULL,        -- Inherited Weighted Method Count
    `wmcnp` INTEGER DEFAULT NULL        -- Non Private Weighted Method Count

);
