export default {
    data() {
        return {
            content: {
                title: 'Job Title',
                subtitle: 'Job subtitle or short description',
                details: 'Job type - Location',
                bodySection: [
                    {
                        headings: 'About Job',
                        description: `Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum`,
                    },
                    {
                        headings: 'About Employee',
                        description: `Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum`,
                    },
                    {
                        headings: 'About requirement',
                        description: `Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum`,
                    },
                    {
                        headings: 'Extended Heading',
                        description: `Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum`,
                    }
                ],
            },
            pageStyle: {
                defaultView: [
                    {
                        name: 'Title',
                        key: 'title',
                        fontSize: 50,
                        fontWeight: 700,
                        letterSpacing: 1,
                        color: '#313131'
                    },
                    {
                        name: 'Subtitle',
                        key: 'sub-title',
                        fontSize: 30,
                        fontWeight: 300,
                        letterSpacing: 1,
                        color: '#afb1b6'
                    },
                    {
                        name: 'Details',
                        key: 'details',
                        fontSize: 20,
                        fontWeight: 300,
                        letterSpacing: 1,
                        color: '#3758b3',
                    },
                    {
                        name: 'Headings',
                        key: 'headings',
                        fontSize: 27,
                        fontWeight: 600,
                        letterSpacing: 0,
                        color: '#313131',
                    },
                    {
                        name: 'Description',
                        key: 'description',
                        fontSize: 19,
                        fontWeight: 300,
                        letterSpacing: 0,
                        color: '#313131',
                    }
                ],
                mobileView: [
                    {
                        name: 'Title',
                        key: 'title',
                        fontSize: 40,
                        fontWeight: 700,
                        letterSpacing: 1,
                        color: '#313131'
                    },
                    {
                        name: 'Subtitle',
                        key: 'sub-title',
                        fontSize: 25,
                        fontWeight: 300,
                        letterSpacing: 1,
                        color: '#afb1b6'
                    },
                    {
                        name: 'Details',
                        key: 'details',
                        fontSize: 16,
                        fontWeight: 300,
                        letterSpacing: 1,
                        color: '#3758b3',
                    },
                    {
                        name: 'Headings',
                        key: 'headings',
                        fontSize: 20,
                        fontWeight: 600,
                        letterSpacing: 0,
                        color: '#313131',
                    },
                    {
                        name: 'Description',
                        key: 'description',
                        fontSize: 18,
                        fontWeight: 300,
                        letterSpacing: 0,
                        color: '#313131',
                    }
                ],
            },
            pageBlocks: {
                defaultView: {
                    header: true,
                    body: true,
                    footer: true,
                    logo: true
                },
                mobileView: {
                    header: true,
                    body: true,
                    footer: true,
                    logo: true,
                }
            },
        }
    }
}