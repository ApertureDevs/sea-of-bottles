describe('Landing Page Tests', () => {
  beforeEach(() => {
    cy.visit('/landing');
  });

  it('It should contains title', () => {
    cy.get('.jumbotron__main-title').contains('Sea of Bottles');
  });

  it('It should contains header', () => {
    cy.get('.header').should('exist');
  });

  it('It should contains footer', () => {
    cy.get('.footer').should('exist');
  });

  it('It should contains landing', () => {
    cy.get('.landing').should('exist');
  });

  it('It should contains sea counters', () => {
    cy.get('.counter-section').should('exist');
  });
});
