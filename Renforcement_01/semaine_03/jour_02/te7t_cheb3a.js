
const object = {
    first:
    {
        aziz: [{ 3: 4 }, { 12: 3 }, { 20: 5 }],
        said: [{ 8: 3 }, { 11: 4 }],
        tayeb: [{ 1: 4 }, { 2: 5 }, { 6: 3 }]
    }
    ,
    second:
    {
        oussama: [{ 20: 3 }, { 25: 5 }],
        ayoub: [{ 18: 4 }, { 14: 3 }],
        hafid: [{ 9: 5 }, { 4: 2 }]
    }
    ,
    third:
    {
        soufiane: [{ 100: 3 }],
        ali: [{ 50: 5 }],
        rida: [{ 10: 4 }, { 12: 3 }]
    }

};
const dakchi = {}
function count(object) {
    for (const tage in object) {
        const tages = object[tage]

        for (const fourmateur in tages) {
            const fourmateurs = tages[fourmateur]

            // console.log(fourmateurs)
            fourmateurs.forEach(chaire => {
                const key = Object.values(chaire)
                const values = Object.keys(chaire)
                console.log(key)
                console.log(values)

                // if (!dakchi[key]) {
                //     dakchi[key] = 0
                // }
                // dakchi += Number()
            });
        }
    }
}

count(object)
// objectif d'aujourdh'ui c'est numero de chaire de meme type et numero de foot
// aziz: [{ 3: 4 }, { 12: 3 }, { 20: 5 }], nom de fourmateur : [{numero de chaire : numero des foots}]