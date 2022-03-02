describe('Registration', () => {

    it('successfully loads', () => {
        cy.visit('register.php')
    })

    it('validates date picker functionality', () => {
        cy.get('#dob').click()
        // Test Date
        cy.get('select[class=ui-datepicker-year]').select('1999')
        cy.get('select[class=ui-datepicker-month]').select('10')
        cy.get('a[data-date="11"]').click()
    })

    it('fills out registration details', () => {

        cy.get('#first_name')
            .should('be.visible')
            .type('John')

        cy.get('#last_name')
            .should('be.visible')
            .type('Doe')

        cy.get('#username')
            .should('be.visible')
            .type('JohnDoe99')
        cy.get('#contact')
            .should('be.visible')
            .type('07700900537')

        cy.get('#email')
            .should('be.visible')
            .type('john-doe@example.com')

        cy.get('#password')
            .should('be.visible')
            .type('password_test')

        cy.get('#password_validate')
            .should('be.visible')
            .type('password_test')

        cy.get('button[name="register"]')
            .should('be.visible')
            .click()

        // cy.contains('Failed!').should('not.exist');
        //  cy.contains('Success!').should('exist');


    })
})


describe('Log in', () => {
    it('Logging in', () => {
        cy.visit('login.php')
        cy.get('input[name=email]')
            .should('be.visible')
            .type('john-doe@example.com')

        cy.get('input[name=password]')
            .should('be.visible')
            .type('password_test')

        cy.get('button[name=login]')
            .should('be.visible')
            .click()
    })
})


describe('Release Selection', () => {

    it('All Titles - Page Loads', () => {
        cy.visit("titles.php?type=movies")
    })

    it('Release Card - Show Collapsed Content', () => { 
        cy.get('img[class="card-img"]').first().scrollIntoView().click({ force: true })
    })

    it('Release Card - Show Trailer', () => {
        cy.get('button[data-target="#v_modal"]').first().click({ force: true })
        cy.get('div[id="v_modal"]').should('be.visible')
    })

})
