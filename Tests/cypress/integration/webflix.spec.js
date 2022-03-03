

//We must retain cookies so that between tests, the user remains logged in
//Without the user being logged in, the user cannot make comments and the comments related tests will fail
Cypress.Cookies.defaults({
    preserve: 'webflix_session',
})


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
            .type('JohnDoe99').blur()
        cy.get('#contact')
            .should('be.visible')
            .type('07700900537')

        cy.get('#email')
            .should('be.visible')
            .type('john-doe@example.com').blur()

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

    it('successfully loads', () => {
        cy.visit('https://craig.software/webflix/login.php')
    })

    it('Logging in', () => {
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

    it('Release Card - Redirect to Release page', () => {
        cy.get('a[name="info"]').first().click({ force: true })
        cy.url({ decode: true }).should('contain', 'release.php')
    })

})

describe('Review', () => {

    const commentContent = "Wow! This was such a good movie!";

    it('Write Comment', () => {
        cy.get('textarea[id="comment"]').first().scrollIntoView().type(commentContent)
    })

    it('Select Rating (5 Stars)', () => {
        cy.get('input[value="5"]').click({ force: true }); //Force click due to mdandatory styling. 
    })

    it('Submit Review', () => {
        cy.get('button[name="add_comment"]')
            .should('be.visible')
            .click()
    })

    it('Validate Successful Review', () => {
        cy.contains(commentContent)
            .should('exist')
            .click()
    })


    it('Delete Review', () => {
        cy.get('button[name="user_del_comment"]')
            .should('be.visible')
            .click()
    })

    it('Validate Deleted Review', () => {
        cy.contains(commentContent)
            .should('not.exist')
    })

    it('validates standard users cannot access Admin Page', () => {
        cy.visit('admin.php')
        cy.contains("You do not have permission to access the intended page.")
            .should('exist')
    })


})


describe('Log out', () => {
    it('successfully loads', () => {
        cy.visit('https://craig.software/webflix/logout.php')
    })
})

describe('Guest User Access', () => {

    it('validates guests cannot access Admin Page', () => {
        cy.visit('admin.php')
        cy.contains("You do not have permission to access the intended page.")
            .should('exist')
    })

    it('validates guests cannot make comments on releases', () => {
        cy.visit('release.php?id=1')
        cy.get('button[name="add_comment"]')
            .should('not.visible')
    })
})
