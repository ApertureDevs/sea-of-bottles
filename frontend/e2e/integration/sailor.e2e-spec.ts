const generateRandomEmail = () => {
  const random = Math.random().toString(36).substr(2, 10);

  return `${random}@test.com`;
};

const email = generateRandomEmail();

describe('Create Sailor Page Tests', () => {
  beforeEach(() => {
    cy.visit('/sailor/create');
  });

  it('It should contains title', () => {
    cy.contains('Become a Sailor');
  });

  it('It should contains header', () => {
    cy.get('.header').should('exist');
  });

  it('It should contains footer', () => {
    cy.get('.footer').should('exist');
  });

  it('It should contains create-sailor component', () => {
    cy.get('.create-sailor').should('exist');
  });

  it('should contains a valid creation sailor form', () => {
    cy.get('#email').type(email);
    cy.get('button[type=submit]').click();
    cy.get('.success').should('exist');
    cy.get('.success').should('be.visible');
  });
});

describe('Delete Sailor Page Tests', () => {
  beforeEach(() => {
    cy.visit('/sailor/delete');
  });

  it('It should contains title', () => {
    cy.contains('Unsubscribe');
  });

  it('It should contains header', () => {
    cy.get('.header').should('exist');
  });

  it('It should contains footer', () => {
    cy.get('.footer').should('exist');
  });

  it('It should contains delete-sailor component', () => {
    cy.get('.delete-sailor').should('exist');
  });

  it('should contains a valid deletion sailor form', () => {
    cy.get('#email').type(email);
    cy.get('button[type=submit]').click();
    cy.get('.success').should('exist');
    cy.get('.success').should('be.visible');
  });
});
